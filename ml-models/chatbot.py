from flask import Flask, request, jsonify
from flask_cors import CORS
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.sequence import pad_sequences
import pickle

app = Flask(__name__)

# Enable CORS for all routes
CORS(app)

# Load the model
model = load_model('ecommerce_chatbot_model.h5')

# Load the tokenizer and intent_labels
with open('tokenizer.pkl', 'rb') as f:
    tokenizer = pickle.load(f)

with open('intent_labels.pkl', 'rb') as f:
    intent_labels = pickle.load(f)

# Define max_length based on your training data
max_length = 100  # Adjust based on your training

@app.route('/chatbot/predict', methods=['POST'])
def predict():
    data = request.json
    instruction = data['instruction']
    
    sequence = tokenizer.texts_to_sequences([instruction])
    padded_sequence = pad_sequences(sequence, maxlen=max_length)
    prediction = model.predict(padded_sequence)
    intent_idx = prediction.argmax()
    
    return jsonify({'intent': intent_labels[intent_idx]})

if __name__ == '__main__':
    app.run(port=5000)
