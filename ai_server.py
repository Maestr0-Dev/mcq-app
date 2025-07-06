from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/api/ai', methods=['POST'])
def get_ai_response():
    data = request.get_json()
    message = data['message']
    # Replace with actual AI logic here
    response = f"AI Response: {message}"
    return jsonify({'response': response})

if __name__ == '__main__':
    app.run(debug=True, port=5000)
