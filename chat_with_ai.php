<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Braze AI</title>
  <style>
    :root {
      --primary: #2563eb;
      --primary-light: #3b82f6;
      --primary-dark: #1d4ed8;
      --light: #f3f4f6;
      --dark: #111827;
      --gray: #6b7280;
      --light-gray: #e5e7eb;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    body {
      display: flex;
      flex-direction: column;
      height: 100vh;
      background-color: var(--light);
      color: var(--dark);
    }
    
    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 1.5rem;
      background-color: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      z-index: 10;
    }
    
    .logo-container {
      display: flex;
      align-items: center;
    }
    
    .home-link {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1.25rem;
      color: var(--gray);
      transition: color 0.2s;
    }
    
    .home-link:hover {
      color: var(--primary);
    }
    
    .logo {
      display: flex;
      align-items: center;
      font-weight: 700;
      font-size: 1.25rem;
      color: var(--dark);
    }
    
    .logo svg {
      margin-right: 0.75rem;
    }
    
    .agent-toggle {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 48px;
      height: 24px;
    }
    
    .toggle-switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .toggle-slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: var(--light-gray);
      transition: .4s;
      border-radius: 24px;
    }
    
    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
      background-color: var(--primary);
    }
    
    input:checked + .toggle-slider:before {
      transform: translateX(24px);
    }
    
    .toggle-label {
      font-size: 0.875rem;
      font-weight: 500;
    }
    
    .chat-container {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 1rem;
      overflow-y: auto;
      gap: 1rem;
      max-width: 900px;
      margin: 0 auto;
      width: 100%;
    }
    
    .message {
      display: flex;
      gap: 1rem;
      animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    
    .user-avatar {
      background-color: var(--light-gray);
      color: var(--gray);
    }
    
    .ai-avatar {
      background-color: var(--primary-light);
      color: white;
    }
    
    .message-content {
      background-color: white;
      padding: 1rem;
      border-radius: 0.75rem;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      max-width: 80%;
      line-height: 1.5;
    }
    
    .ai-message .message-content {
      background-color: var(--primary);
      color: white;
    }
    
    .user-message {
      justify-content: flex-end;
    }
    
    .user-message .message-content {
      background-color: white;
    }
    
    .agent-mode-indicator {
      display: none;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      background-color: rgba(37, 99, 235, 0.1);
      border-radius: 0.5rem;
      color: var(--primary-dark);
      font-size: 0.875rem;
      font-weight: 500;
      animation: pulse 2s infinite;
      margin: 0 auto 1rem auto;
      width: fit-content;
    }
    
    .agent-active .agent-mode-indicator {
      display: flex;
    }
    
    @keyframes pulse {
      0% { opacity: 0.8; }
      50% { opacity: 1; }
      100% { opacity: 0.8; }
    }
    
    .input-container {
      display: flex;
      padding: 1rem;
      background-color: white;
      border-top: 1px solid var(--light-gray);
      position: sticky;
      bottom: 0;
    }
    
    .input-wrapper {
      display: flex;
      align-items: center;
      width: 100%;
      max-width: 900px;
      margin: 0 auto;
      position: relative;
    }
    
    .message-input {
      flex: 1;
      padding: 0.75rem 3rem 0.75rem 1rem;
      border: 1px solid var(--light-gray);
      border-radius: 1.5rem;
      font-size: 1rem;
      resize: none;
      outline: none;
      max-height: 120px;
      overflow-y: auto;
    }
    
    .message-input:focus {
      border-color: var(--primary-light);
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    .action-buttons {
      position: absolute;
      right: 0.5rem;
      bottom: 0.5rem;
      display: flex;
      gap: 0.5rem;
    }
    
    .icon-button {
      background-color: var(--primary);
      color: white;
      border: none;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .icon-button:hover {
      background-color: var(--primary-dark);
    }
    
    .icon-button.secondary {
      background-color: var(--light-gray);
      color: var(--gray);
    }
    
    .icon-button.secondary:hover {
      background-color: var(--gray);
      color: white;
    }
    
    /* File upload panel */
    .file-upload-panel {
      display: none;
      position: absolute;
      bottom: 60px;
      right: 0;
      background-color: white;
      padding: 1rem;
      border-radius: 0.75rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 250px;
      z-index: 100;
      animation: slideUp 0.3s ease;
    }
    
    .file-upload-panel.active {
      display: block;
    }
    
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .file-upload-panel h3 {
      font-size: 0.875rem;
      margin-bottom: 0.75rem;
      color: var(--dark);
    }
    
    .file-types {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .file-type-btn {
      background-color: var(--light);
      border: none;
      padding: 0.5rem;
      border-radius: 0.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      cursor: pointer;
      transition: all 0.2s;
      width: 70px;
    }
    
    .file-type-btn:hover {
      background-color: var(--light-gray);
    }
    
    .file-type-btn span {
      font-size: 0.75rem;
      margin-top: 0.25rem;
    }
    
    .file-upload-input {
      display: none;
    }
    
    .upload-btn {
      background-color: var(--primary);
      color: white;
      border: none;
      padding: 0.5rem;
      border-radius: 0.5rem;
      width: 100%;
      font-size: 0.875rem;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .upload-btn:hover {
      background-color: var(--primary-dark);
    }
    
    @media (max-width: 640px) {
      .message-content {
        max-width: 90%;
      }
      
      .toggle-label {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="logo-container">
      <a href="home.php" class="home-link" title="Back to Home">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
      </a>
      <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2c1.9 0 3.6.7 4.9 2 1.3 1.3 2 3 2.1 4.9v.1l.7 1.5c.2.6.3 1 .3 1.5 0 .5-.1.9-.3 1.5l-.7 1.5v.1C19 17 16.2 20 12 20c-4.2 0-7-3-7.9-5.9v-.1l-.7-1.5C3.1 11.9 3 11.5 3 11c0-.5.1-.9.3-1.5l.7-1.5v-.1C4.8 4.7 8.1 2 12 2z"/>
          <path d="M12 8c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
          <path d="M12 16a2 2 0 0 0 0-4"/>
        </svg>
        <span>Braze AI</span>
      </div>
    </div>
    <div class="agent-toggle">
      <span class="toggle-label">Agent Mode</span>
      <label class="toggle-switch">
        <input type="checkbox" id="agent-toggle">
        <span class="toggle-slider"></span>
      </label>
    </div>
  </div>
  
  <div class="agent-mode-indicator">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M12 5v14"></path>
      <path d="M5 12h14"></path>
    </svg>
    Agent Mode Active
  </div>
  
  <div class="chat-container" id="chat-container">
    <div class="message ai-message">
      <div class="avatar ai-avatar">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2c1.9 0 3.6.7 4.9 2 1.3 1.3 2 3 2.1 4.9v.1l.7 1.5c.2.6.3 1 .3 1.5 0 .5-.1.9-.3 1.5l-.7 1.5v.1C19 17 16.2 20 12 20c-4.2 0-7-3-7.9-5.9v-.1l-.7-1.5C3.1 11.9 3 11.5 3 11c0-.5.1-.9.3-1.5l.7-1.5v-.1C4.8 4.7 8.1 2 12 2z"/>
          <path d="M12 8c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
        </svg>
      </div>
      <div class="message-content">
        Hello! I'm Braze AI. How can I assist you today?
      </div>
    </div>
  </div>
  
  <div class="file-upload-panel" id="file-upload-panel">
    <h3>Upload a file</h3>
    <div class="file-types">
      <button class="file-type-btn" data-type="image">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <circle cx="8.5" cy="8.5" r="1.5"></circle>
          <polyline points="21 15 16 10 5 21"></polyline>
        </svg>
        <span>Image</span>
      </button>
      <button class="file-type-btn" data-type="document">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Doc</span>
      </button>
      <button class="file-type-btn" data-type="audio">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2v12"></path>
          <path d="M8 14h8"></path>
          <path d="M12 14v8"></path>
          <path d="M17.4 7.6A8 8 0 1 1 6.6 6.6"></path>
        </svg>
        <span>Audio</span>
      </button>
    </div>
    <input type="file" id="file-upload-input" class="file-upload-input">
    <button class="upload-btn">Upload</button>
  </div>
  
  <div class="input-container">
    <div class="input-wrapper">
      <textarea class="message-input" id="message-input" placeholder="Type your message..." rows="1"></textarea>
      <div class="action-buttons">
        <button class="icon-button secondary" id="attachment-button" title="Upload File">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
          </svg>
        </button>
        <button class="icon-button" id="send-button" title="Send Message">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"></line>
            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <script>
    const chatContainer = document.getElementById('chat-container');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const agentToggle = document.getElementById('agent-toggle');
    const attachmentButton = document.getElementById('attachment-button');
    const fileUploadPanel = document.getElementById('file-upload-panel');
    const fileUploadInput = document.getElementById('file-upload-input');
    const fileTypeBtns = document.querySelectorAll('.file-type-btn');
    
    // Handle input resizing
    messageInput.addEventListener('input', function() {
      this.style.height = 'auto';
      this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
    
    // Handle agent mode toggle
    agentToggle.addEventListener('change', function() {
      document.body.classList.toggle('agent-active', this.checked);
      
      if (this.checked) {
        addMessage('ai', 'Agent mode activated. I can now proactively help with your tasks.');
      } else {
        addMessage('ai', 'Agent mode deactivated. I\'ll wait for your instructions.');
      }
    });
    
    // Handle attachment button
    attachmentButton.addEventListener('click', function() {
      fileUploadPanel.classList.toggle('active');
    });
    
    // Handle file type selection
    fileTypeBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const fileType = this.getAttribute('data-type');
        let accept = '';
        
        switch(fileType) {
          case 'image':
            accept = 'image/*';
            break;
          case 'document':
            accept = '.pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx';
            break;
          case 'audio':
            accept = 'audio/*';
            break;
        }
        
        fileUploadInput.setAttribute('accept', accept);
        fileUploadInput.click();
      });
    });
    
    // Handle file selection
    fileUploadInput.addEventListener('change', function() {
      if (this.files.length > 0) {
        const fileName = this.files[0].name;
        addMessage('user', `I'm uploading: ${fileName}`);
        fileUploadPanel.classList.remove('active');
        
        // Simulate AI response about the file
        setTimeout(() => {
          addMessage('ai', `I've received your file "${fileName}". What would you like me to do with it?`);
        }, 1000);
      }
    });
    
    // Click outside to close panel
    document.addEventListener('click', function(e) {
      if (!fileUploadPanel.contains(e.target) && e.target !== attachmentButton) {
        fileUploadPanel.classList.remove('active');
      }
    });
    
    // Handle sending messages
    function sendMessage() {
      const message = messageInput.value.trim();
      if (message) {
        addMessage('user', message);
        messageInput.value = '';
        messageInput.style.height = 'auto';
        
        // Simulate AI response (in a real app, this would be an API call)
        setTimeout(() => {
          addMessage('ai', getResponse(message));
        }, 1000);
      }
    }
    
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });
    
    // Add a message to the chat
    function addMessage(type, content) {
      const messageDiv = document.createElement('div');
      messageDiv.classList.add('message');
      
      if (type === 'user') {
        messageDiv.classList.add('user-message');
        messageDiv.innerHTML = `
          <div class="message-content">${content}</div>
          <div class="avatar user-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
        `;
      } else {
        messageDiv.classList.add('ai-message');
        messageDiv.innerHTML = `
          <div class="avatar ai-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2c1.9 0 3.6.7 4.9 2 1.3 1.3 2 3 2.1 4.9v.1l.7 1.5c.2.6.3 1 .3 1.5 0 .5-.1.9-.3 1.5l-.7 1.5v.1C19 17 16.2 20 12 20c-4.2 0-7-3-7.9-5.9v-.1l-.7-1.5C3.1 11.9 3 11.5 3 11c0-.5.1-.9.3-1.5l.7-1.5v-.1C4.8 4.7 8.1 2 12 2z"/>
              <path d="M12 8c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
            </svg>
          </div>
          <div class="message-content">${content}</div>
        `;
      }
      
      chatContainer.appendChild(messageDiv);
      chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    
    // Simple response function (would be replaced with actual AI response in a real app)
    function getResponse(message) {
      const isAgentMode = agentToggle.checked;
      
      if (message.toLowerCase().includes('hello') || message.toLowerCase().includes('hi')) {
        return isAgentMode ? 
          "Hello! I'm in agent mode and ready to help. I notice you're starting a conversation. Is there a specific task you'd like me to help with today?" : 
          "Hello! How can I assist you today?";
      } else if (message.toLowerCase().includes('help')) {
        return isAgentMode ?
          "I'm analyzing what you might need help with. Could you tell me more about what you're trying to accomplish? In agent mode, I can be more proactive in suggesting solutions." :
          "I'd be happy to help. What do you need assistance with?";
      } else {
        return isAgentMode ?
          "I'm processing your request in agent mode. This allows me to take more initiative in helping you solve problems. Based on what you've shared, would you like me to suggest next steps?" :
          "Thank you for your message. How would you like me to help with this?";
      }
    }
  </script>
</body>
</html>