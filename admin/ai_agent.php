<?php
/**
 * AI Content Fetcher Agent
 * Fetches educational definitions and content from various sources
 * and inserts them into the database for the study platform
 */

// Include the DB class
require_once '../classes.php';

class AIContentFetcher {
    private $db;
    private $apiKeys;
    private $logFile;
    
    public function __construct() {
        $this->db = new DB();
        $this->logFile = 'content_fetcher.log';
        
        // Configure your API keys here
        $this->apiKeys = [
            'openai' => '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
',
            'wikipedia' => null // Wikipedia doesn't require API key
        ];
    }
    
    /**
     * Main function to fetch and store content
     */
    public function fetchAndStoreContent($subject, $category, $topics = []) {
        $this->log("Starting content fetch for Subject: $subject, Category: $category");
        
        if (empty($topics)) {
            $topics = $this->getTopicsForSubject($subject, $category);
        }
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($topics as $topic) {
            try {
                $this->log("Processing topic: $topic");
                
                // Try multiple sources for comprehensive content
                $content = $this->fetchFromMultipleSources($topic, $subject, $category);
                
                if ($content) {
                    $this->storeContent($subject, $category, $topic, $content);
                    $successCount++;
                    $this->log("Successfully stored content for: $topic");
                } else {
                    $errorCount++;
                    $this->log("Failed to fetch content for: $topic");
                }
                
                // Rate limiting - pause between requests
                sleep(2);
                
            } catch (Exception $e) {
                $errorCount++;
                $this->log("Error processing $topic: " . $e->getMessage());
            }
        }
        
        $this->log("Completed. Success: $successCount, Errors: $errorCount");
        return ['success' => $successCount, 'errors' => $errorCount];
    }
    
    /**
     * Fetch content from multiple sources
     */
    private function fetchFromMultipleSources($topic, $subject, $category) {
        $sources = [
            'openai' => $this->fetchFromOpenAI($topic, $subject, $category),
            'wikipedia' => $this->fetchFromWikipedia($topic),
            'educational_apis' => $this->fetchFromEducationalAPIs($topic, $subject),
            'fallback' => $this->generateFallbackContent($topic, $subject, $category)
        ];
        
        // Combine and format the content
        return $this->combineContent($sources, $topic);
    }
    
    /**
     * Fetch content using OpenAI API
     */
    private function fetchFromOpenAI($topic, $subject, $category) {
        if (empty($this->apiKeys['openai']) || $this->apiKeys['openai'] === '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') {
            $this->log("OpenAI API key not configured, skipping OpenAI fetch");
            return null;
        }
        
        try {
            $prompt = $this->buildPrompt($topic, $subject, $category);
            
            $data = [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an educational content expert. Provide comprehensive, accurate definitions and explanations suitable for students.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7
            ];
            
            $this->log("Sending request to OpenAI for topic: $topic");
            
            $response = $this->makeAPICall(
                'https://api.openai.com/v1/chat/completions',
                $data,
                [
                    'Authorization: Bearer ' . $this->apiKeys['openai'],
                    'Content-Type: application/json'
                ]
            );
            
            if ($response && isset($response['choices'][0]['message']['content'])) {
                return $response['choices'][0]['message']['content'];
            } else {
                $this->log("OpenAI: No content in response for topic: $topic");
                return null;
            }
        } catch (Exception $e) {
            $this->log("OpenAI API error for topic '$topic': " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Fetch content from Wikipedia
     */
    private function fetchFromWikipedia($topic) {
        try {
            // Clean the topic for URL encoding
            $cleanTopic = trim($topic);
            $cleanTopic = str_replace(' ', '_', $cleanTopic);
            
            // Wikipedia API endpoint
            $url = "https://en.wikipedia.org/api/rest_v1/page/summary/" . urlencode($cleanTopic);
            
            $this->log("Fetching from Wikipedia: $url");
            
            $response = $this->makeAPICall($url, null, [
                'User-Agent: Educational Content Fetcher 1.0',
                'Accept: application/json'
            ], 'GET');
            
            if ($response && isset($response['extract']) && !empty($response['extract'])) {
                return [
                    'definition' => $response['extract'],
                    'source' => 'Wikipedia',
                    'url' => $response['content_urls']['desktop']['page'] ?? ''
                ];
            } else {
                $this->log("Wikipedia: No extract found for topic: $topic");
                return null;
            }
        } catch (Exception $e) {
            $this->log("Wikipedia API error for topic '$topic': " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Fetch from educational APIs (Khan Academy, etc.)
     */
    private function fetchFromEducationalAPIs($topic, $subject) {
        // Try to fetch from free educational resources
        try {
            // Example: Try to get content from Khan Academy's public API
            $khanUrl = "https://www.khanacademy.org/api/internal/graphql/getTopicBySlug";
            // Note: This is a simplified example. You'd need to implement proper Khan Academy API integration
            
            // For now, we'll return null and rely on other sources
            return null;
        } catch (Exception $e) {
            $this->log("Educational APIs error: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Generate fallback content when APIs are unavailable
     */
    private function generateFallbackContent($topic, $subject, $category) {
        // This creates basic structured content when APIs fail
        $fallbackContent = "<h4>$topic</h4>";
        $fallbackContent .= "<p><strong>Subject:</strong> " . ucfirst($subject) . "</p>";
        $fallbackContent .= "<p><strong>Category:</strong> " . ucfirst($category) . "</p>";
        $fallbackContent .= "<p><em>This is a placeholder entry for '$topic'. ";
        $fallbackContent .= "Please update with proper content when API access is available.</em></p>";
        
        // Add some basic context based on subject
        switch ($subject) {
            case 'biology':
                $fallbackContent .= "<p>This is a biological concept related to living organisms and life processes.</p>";
                break;
            case 'physics':
                $fallbackContent .= "<p>This is a physics concept related to matter, energy, and their interactions.</p>";
                break;
            case 'chemistry':
                $fallbackContent .= "<p>This is a chemistry concept related to atoms, molecules, and chemical reactions.</p>";
                break;
            case 'computer_science':
                $fallbackContent .= "<p>This is a computer science concept related to computing, algorithms, and data processing.</p>";
                break;
            default:
                $fallbackContent .= "<p>This is an educational concept that requires further research and definition.</p>";
        }
        
        return [
            'definition' => $fallbackContent,
            'source' => 'Fallback Generator',
            'url' => ''
        ];
    }
    
    /**
     * Build prompt for AI content generation
     */
    private function buildPrompt($topic, $subject, $category) {
        return "Please provide a comprehensive definition and explanation of '$topic' in the context of $subject, specifically for the $category category. Include:

1. A clear, concise definition
2. Key concepts and principles
3. Real-world applications or examples
4. Common misconceptions (if any)
5. Related terms or concepts

Format the response in HTML with proper headings and structure. Make it suitable for students studying $subject.";
    }
    
    /**
     * Combine content from multiple sources
     */
    private function combineContent($sources, $topic) {
        $combinedContent = "<h3>$topic</h3>";
        $hasRealContent = false;
        
        foreach ($sources as $source => $content) {
            if ($content && $source !== 'fallback') {
                $hasRealContent = true;
                if (is_array($content)) {
                    $combinedContent .= "<div class='source-section'>";
                    $combinedContent .= "<h4>Source: " . ucfirst($source) . "</h4>";
                    $combinedContent .= "<div class='content'>" . $content['definition'] . "</div>";
                    if (isset($content['url']) && !empty($content['url'])) {
                        $combinedContent .= "<p><a href='" . htmlspecialchars($content['url']) . "' target='_blank'>Read more</a></p>";
                    }
                    $combinedContent .= "</div>";
                } else {
                    $combinedContent .= "<div class='source-section'>";
                    $combinedContent .= "<h4>AI Generated Content</h4>";
                    $combinedContent .= "<div class='content'>" . $content . "</div>";
                    $combinedContent .= "</div>";
                }
            }
        }
        
        // If no real content was found, use fallback
        if (!$hasRealContent && isset($sources['fallback'])) {
            $content = $sources['fallback'];
            $combinedContent .= "<div class='source-section fallback'>";
            $combinedContent .= "<h4>Placeholder Content</h4>";
            $combinedContent .= "<div class='content'>" . $content['definition'] . "</div>";
            $combinedContent .= "</div>";
        }
        
        return $combinedContent;
    }
    
    /**
     * Store content in database
     */
    private function storeContent($subject, $category, $topic, $content) {
        // Check if content already exists
        $existingContent = $this->db->getSpecificContent($subject, $category, $topic);
        
        if ($existingContent) {
            // Update existing content
            return $this->db->updateContent($existingContent['id'], $topic, $content);
        } else {
            // Insert new content
            return $this->db->insertContent($subject, $category, $topic, $content);
        }
    }
    
    /**
     * Get predefined topics for subjects
     */
    private function getTopicsForSubject($subject, $category) {
        $topics = [
            'biology' => [
                'definitions' => [
                    'Cell', 'DNA', 'RNA', 'Protein', 'Enzyme', 'Mitosis', 'Meiosis',
                    'Photosynthesis', 'Cellular Respiration', 'Evolution', 'Natural Selection',
                    'Genetics', 'Chromosome', 'Gene', 'Allele', 'Phenotype', 'Genotype',
                    'Ecosystem', 'Food Chain', 'Biodiversity', 'Taxonomy', 'Prokaryote',
                    'Eukaryote', 'Bacteria', 'Virus', 'Fungi', 'Plant', 'Animal'
                ],
                'concepts' => [
                    'Theory of Evolution', 'Cell Theory', 'Gene Expression', 'Homeostasis',
                    'Metabolism', 'Reproduction', 'Inheritance', 'Adaptation', 'Speciation',
                    'Ecological Succession', 'Food Web', 'Carbon Cycle', 'Nitrogen Cycle'
                ]
            ],
            'physics' => [
                'definitions' => [
                    'Force', 'Energy', 'Work', 'Power', 'Momentum', 'Velocity', 'Acceleration',
                    'Gravity', 'Friction', 'Pressure', 'Temperature', 'Heat', 'Light',
                    'Sound', 'Electricity', 'Magnetism', 'Atom', 'Electron', 'Proton',
                    'Neutron', 'Wave', 'Frequency', 'Wavelength', 'Amplitude'
                ],
                'concepts' => [
                    'Newton\'s Laws', 'Conservation of Energy', 'Thermodynamics',
                    'Electromagnetic Spectrum', 'Quantum Mechanics', 'Relativity',
                    'Ohm\'s Law', 'Kirchhoff\'s Laws', 'Wave-Particle Duality'
                ]
            ],
            'chemistry' => [
                'definitions' => [
                    'Atom', 'Molecule', 'Element', 'Compound', 'Ion', 'Isotope',
                    'Periodic Table', 'Atomic Number', 'Mass Number', 'Valence',
                    'Bond', 'Covalent Bond', 'Ionic Bond', 'Metallic Bond',
                    'Acid', 'Base', 'pH', 'Catalyst', 'Reaction Rate', 'Equilibrium'
                ],
                'concepts' => [
                    'Atomic Theory', 'Chemical Bonding', 'Stoichiometry',
                    'Thermochemistry', 'Kinetics', 'Equilibrium', 'Acids and Bases',
                    'Electrochemistry', 'Organic Chemistry', 'Inorganic Chemistry'
                ]
            ],
            'computer_science' => [
                'definitions' => [
                    'Algorithm', 'Data Structure', 'Variable', 'Function', 'Loop',
                    'Conditional', 'Array', 'Object', 'Class', 'Inheritance',
                    'Polymorphism', 'Encapsulation', 'Database', 'Query', 'API',
                    'Network', 'Protocol', 'HTTP', 'HTML', 'CSS', 'JavaScript'
                ],
                'concepts' => [
                    'Object-Oriented Programming', 'Functional Programming',
                    'Data Structures and Algorithms', 'Database Design',
                    'Web Development', 'Software Engineering', 'Computer Networks',
                    'Operating Systems', 'Computer Architecture', 'Cybersecurity'
                ]
            ]
        ];
        
        return $topics[$subject][$category] ?? [];
    }
    
    /**
     * Make API call
     */
    private function makeAPICall($url, $data = null, $headers = [], $method = 'POST') {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if ($method === 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_error($ch)) {
            curl_close($ch);
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: $httpCode - Response: " . substr($response, 0, 200));
        }
        
        // Check if response is valid JSON
        if (empty($response)) {
            throw new Exception("Empty response received");
        }
        
        // Log the raw response for debugging
        $this->log("Raw API Response: " . substr($response, 0, 500));
        
        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg() . " - Response: " . substr($response, 0, 200));
        }
        
        return $decoded;
    }
    
    /**
     * Log messages
     */
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        echo $logMessage; // Also output to console
    }
    
    /**
     * Get statistics
     */
    public function getStats() {
        return [
            'total_subjects' => $this->db->getTotalSubjects(),
            'total_categories' => $this->db->getTotalCategories(),
            'total_content_items' => $this->db->getTotalContentItems(),
            'last_updated' => $this->db->getLastUpdateTime()
        ];
    }
    
    /**
     * Clean up old or duplicate content
     */
    public function cleanupContent() {
        // Remove duplicate content
        $this->db->removeDuplicateContent();
        
        // Remove empty content
        $this->db->removeEmptyContent();
        
        $this->log("Content cleanup completed");
    }
}

// Usage example and CLI interface
if (php_sapi_name() === 'cli') {
    echo "AI Content Fetcher Agent\n";
    echo "========================\n\n";
    
    $fetcher = new AIContentFetcher();
    
    // Get command line arguments
    $subject = $argv[1] ?? null;
    $category = $argv[2] ?? null;
    $topics = array_slice($argv, 3);
    
    if (!$subject || !$category) {
        echo "Usage: php ai_content_fetcher.php <subject> <category> [topic1] [topic2] ...\n";
        echo "Example: php ai_content_fetcher.php biology definitions\n";
        echo "Example: php ai_content_fetcher.php physics concepts Force Energy\n\n";
        
        echo "Available subjects: biology, physics, chemistry, computer_science\n";
        echo "Available categories: definitions, concepts, examples\n";
        echo "\nNote: Script will work with Wikipedia only if OpenAI API key is not configured.\n";
        exit(1);
    }
    
    echo "Fetching content for: $subject -> $category\n";
    
    if (!empty($topics)) {
        echo "Custom topics: " . implode(', ', $topics) . "\n";
    }
    
    echo "\nStarting content fetch...\n";
    
    $result = $fetcher->fetchAndStoreContent($subject, $category, $topics);
    
    echo "\nResults:\n";
    echo "- Successfully processed: " . $result['success'] . " items\n";
    echo "- Errors: " . $result['errors'] . " items\n";
    
    echo "\nDatabase Statistics:\n";
    $stats = $fetcher->getStats();
    foreach ($stats as $key => $value) {
        echo "- " . ucwords(str_replace('_', ' ', $key)) . ": $value\n";
    }
    
    echo "\nContent fetch completed!\n";
} else {
    // Web interface
    if (isset($_GET['action'])) {
        header('Content-Type: application/json');
        
        $fetcher = new AIContentFetcher();
        
        switch ($_GET['action']) {
            case 'fetch':
                $subject = $_GET['subject'] ?? '';
                $category = $_GET['category'] ?? '';
                $result = $fetcher->fetchAndStoreContent($subject, $category);
                echo json_encode($result);
                break;
                
            case 'stats':
                $stats = $fetcher->getStats();
                echo json_encode($stats);
                break;
                
            case 'cleanup':
                $fetcher->cleanupContent();
                echo json_encode(['status' => 'success']);
                break;
                
            default:
                echo json_encode(['error' => 'Invalid action']);
        }
        exit;
    }
    
    // Display web interface
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>AI Content Fetcher</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h1>AI Content Fetcher</h1>
            <div class="alert alert-info">
                <strong>Note:</strong> This tool fetches content from Wikipedia and OpenAI (if configured). 
                It will create placeholder content when APIs are unavailable.
            </div>
            <div class="row">
                <div class="col-md-8">
                    <form id="fetchForm">
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <select class="form-select" id="subject" required>
                                <option value="">Select Subject</option>
                                <option value="biology">Biology</option>
                                <option value="physics">Physics</option>
                                <option value="chemistry">Chemistry</option>
                                <option value="computer_science">Computer Science</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" required>
                                <option value="">Select Category</option>
                                <option value="definitions">Definitions</option>
                                <option value="concepts">Concepts</option>
                                <option value="examples">Examples</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Fetch Content</button>
                        <button type="button" class="btn btn-secondary" onclick="getStats()">Get Stats</button>
                        <button type="button" class="btn btn-warning" onclick="cleanup()">Cleanup</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <div id="status" class="alert alert-info">
                        Ready to fetch content
                    </div>
                    <div id="stats"></div>
                </div>
            </div>
        </div>
        
        <script>
            document.getElementById('fetchForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const subject = document.getElementById('subject').value;
                const category = document.getElementById('category').value;
                
                if (!subject || !category) {
                    alert('Please select both subject and category');
                    return;
                }
                
                document.getElementById('status').innerHTML = 'Fetching content...';
                
                fetch(`?action=fetch&subject=${subject}&category=${category}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('status').innerHTML = 
                            `<strong>Results:</strong><br>
                             Success: ${data.success} items<br>
                             Errors: ${data.errors} items`;
                        getStats();
                    })
                    .catch(error => {
                        document.getElementById('status').innerHTML = 'Error: ' + error.message;
                    });
            });
            
            function getStats() {
                fetch('?action=stats')
                    .then(response => response.json())
                    .then(data => {
                        let html = '<h5>Database Statistics</h5>';
                        for (let key in data) {
                            html += `<strong>${key.replace('_', ' ').toUpperCase()}:</strong> ${data[key]}<br>`;
                        }
                        document.getElementById('stats').innerHTML = html;
                    });
            }
            
            function cleanup() {
                if (confirm('Are you sure you want to cleanup the database?')) {
                    fetch('?action=cleanup')
                        .then(response => response.json())
                        .then(data => {
                            alert('Cleanup completed');
                            getStats();
                        });
                }
            }
            
            // Load stats on page load
            getStats();
        </script>
    </body>
    </html>
    <?php
}
?>