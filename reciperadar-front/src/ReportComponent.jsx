import React, { useState } from 'react';
import { Container, Form, Button, Alert } from 'react-bootstrap';
import { useNavigate } from 'react-router-dom'; 
import axios from 'axios';


function ReportIssueForm() {
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post(
        'https://localhost:8000/api/issues',
        { title, description },
        {
          headers: {
            'Content-Type': 'application/ld+json',
          },
        }
      );
      if (response.status === 201) {
        setMessage('Thank you for your feedback!');
        navigate('/dashboard', { state: { message: 'Thank you for your feedback' } });
      } else {
        setError('Failed to submit report');
      }
    } catch (error) {
      setError('Error: ' + error.message);
    }
  };

  return (
    
      <div className="d-flex justify-content-center mt-5 flex-column report-container">
        <h2>Please send us Your feedback!</h2>
      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="title">
          <Form.Label>Title</Form.Label>
          <Form.Control
            type="text"
            placeholder="Enter title"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
          />
        </Form.Group>
        <Form.Group controlId="description">
          <Form.Label>Description</Form.Label>
          <Form.Control
            as="textarea"
            rows={3}
            placeholder="Enter issue description"
            value={description}
            onChange={(e) => setDescription(e.target.value)}
          />
        </Form.Group>
        <Button className='mt-2' variant="primary" type="submit">
          Submit
        </Button>
      </Form>
      {message && (
        <Alert variant="success" className="mt-3">
          {message}
        </Alert>
      )}
      {error && (
        <Alert variant="danger" className="mt-3">
          {error}
        </Alert>
      )}
      </div>

  );
}

export default ReportIssueForm;
