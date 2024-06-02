import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom'; 
import axios from 'axios';
import { Container, Card, ListGroup, Button } from 'react-bootstrap';
import './assets/styles/AdminPanel.css';

function AdminPanel() {
  const [isAdmin, setIsAdmin] = useState(false);
  const [issues, setIssues] = useState([]);
  const [users, setUsers] = useState([]);
  const navigate = useNavigate(); 

  useEffect(() => {
    const userRole = localStorage.getItem('user_role');
    if (userRole && userRole.includes('ADMIN')) {
      setIsAdmin(true);
      fetchIssues();
      fetchUsers();
    } else {
      navigate('/dashboard'); 
    }
  }, [navigate]);

  const fetchIssues = async () => {
    try {
      const response = await axios.get('https://localhost:8000/api/issues');
      if (response.status === 200) {
        const unresolvedIssues = response.data['hydra:member'].filter(issue => !issue.isResolved);
        setIssues(unresolvedIssues);
      } else {
        console.error('Failed to fetch issues');
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const fetchUsers = async () => {
    try {
      const response = await axios.get('https://localhost:8000/api/users');
      if (response.status === 200) {
        setUsers(response.data['usersData']);
      } else {
        console.error('Failed to fetch users');
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const handleResolveIssue = async (issueId) => {
    try {
      const response = await axios.post(`https://localhost:8000/api/issues/${issueId}/update_status`, {
        isResolved: true,
      },
      {
        headers: {
          'Content-Type': 'application/ld+json',
        },
      });
      if (response.status === 201) {
        setIssues(prevIssues => prevIssues.filter(issue => issue.id !== issueId));
        console.log('Issue resolved successfully');
        fetchIssues();
      } else {
        console.error('Failed to resolve issue');
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <div className="admin-panel-container">
      {isAdmin ? (
        <>
          <div className="user-list">
            <h2>Registered Users</h2>
            <ListGroup>
              {users.map(user => (
                <Card key={user.id} className="mb-3">
                  <Card.Body>
                    <Card.Text><strong>Email:</strong> {user.email}</Card.Text>
                    <Card.Text><strong>Created At:</strong> {new Date(user.created_at.date).toLocaleString()}</Card.Text>
                  </Card.Body>
                </Card>
              ))}
            </ListGroup>
          </div>
          <div className="issue-list">
            <h2 className='text-center'>Unresolved Issues</h2>
            <ListGroup>
              {issues.map(issue => (
                <Card key={issue.id} className="mb-3">
                  <Card.Body>
                    <Card.Title>{issue.title}</Card.Title>
                    <Card.Text>{issue.description}</Card.Text>
                    <Button className='mark-resolved' variant="success" onClick={() => handleResolveIssue(issue.id)}>
                      Mark as Resolved
                    </Button>
                  </Card.Body>
                </Card>
              ))}
            </ListGroup>
          </div>
        </>
      ) : null}
    </div>
  );
}

export default AdminPanel;
