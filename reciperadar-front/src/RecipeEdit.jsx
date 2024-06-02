import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Card, Button, Form } from 'react-bootstrap';
import { useParams } from 'react-router-dom';
import useAuth from './useAuth';
import './assets/styles/RecipeDetail.css';
import { useNavigate } from 'react-router-dom'; 

function RecipeDetail({ isAuthenticated }) {
  useAuth(isAuthenticated);
  const { id } = useParams();
  const navigate = useNavigate(); 
  const [recipe, setRecipe] = useState(null);
  const [loading, setLoading] = useState(true);
  const [editMode, setEditMode] = useState(false);
  const [editedRecipe, setEditedRecipe] = useState(null);
  const [commentContent, setCommentContent] = useState('');

  useEffect(() => {
    const fetchRecipe = async () => {
      try {
        const response = await axios.get(`https://localhost:8000/api/recipes/${id}`);
        const recipeData = response.data;
        setRecipe(recipeData);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching recipe:', error);
        setLoading(false);
      }
    };

    fetchRecipe();
  }, [id]);

  const handleChange = (e) => {
    setCommentContent(e.target.value);
  };

  const handleDeleteRecipe = async () => {
    try {
      await axios.delete(`https://localhost:8000/api/recipes/${id}`);
      console.log('Recipe deleted successfully');
      navigate('/');
    } catch (error) {
      console.error('Error deleting recipe:', error);
    }
  };

  const handleEditRecipe = () => {
    setEditedRecipe(recipe);
    setEditMode(true);
  };

  const handleCancelEdit = () => {
    setEditedRecipe(null);
    setEditMode(false);
  };

  const handleSaveEdit = async () => {
    try {
      await axios.put(`https://localhost:8000/api/recipes/${id}`, editedRecipe);
      console.log('Recipe updated successfully');
      setRecipe(editedRecipe);
      setEditedRecipe(null);
      setEditMode(false);
    } catch (error) {
      console.error('Error updating recipe:', error);
    }
  };

  const handleSubmitComment = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(`https://localhost:8000/api/recipes/${id}/comments`, {
        content: commentContent,
      });
      console.log('Comment added:', response.data);
      setCommentContent('');
      const refreshedRecipe = await axios.get(`https://localhost:8000/api/recipes/${id}`);
      setRecipe(refreshedRecipe.data);
    } catch (error) {
      console.error('Error adding comment:', error);
    }
  };

  const handleDeleteComment = async (commentId) => {
    try {
      await axios.delete(`https://localhost:8000/api/comments/${commentId}`);
      console.log('Comment deleted successfully');
      const refreshedRecipe = await axios.get(`https://localhost:8000/api/recipes/${id}`);
      setRecipe(refreshedRecipe.data);
    } catch (error) {
      console.error('Error deleting comment:', error);
    }
  };

  if (loading || !recipe) {
    return <div>Loading...</div>;
  }

  const user_id = localStorage.getItem('user_id')
  const user_role = localStorage.getItem('user_role')
  const creatorId = recipe.creator.id;
  const isCreator = parseInt(user_id) === parseInt(creatorId);
  const isAdmin = user_role.includes('ADMIN');

  return (
    <div className="container mt-4">
      {recipe && (
        <>
          <h1 className="mb-4">{recipe.name}</h1>
          <div className="row">
            <div className="col-md-8">
              <Card>
                <Card.Body>
                  <Card.Title>Description</Card.Title>
                  <Card.Text>{recipe.description}</Card.Text>
                </Card.Body>
              </Card>
              <Card className="mt-4">
                <Card.Body>
                  <Card.Title>Ingredients</Card.Title>
                  <ul>
                    {recipe.ingredients.map((ingredient, index) => (
                      <li key={index}>{ingredient.name}</li>
                    ))}
                  </ul>
                </Card.Body>
              </Card>
            </div>
            <div className="col-md-4">
              <Card className="conditional-mt-3">
                <Card.Body>
                  <Card.Title>Type of Cuisine</Card.Title>
                  <Card.Text>{recipe.typeOfCuisine.name}</Card.Text>
                </Card.Body>
              </Card>
              <Card className="mt-4">
                <Card.Body>
                  <Card.Title>Comments</Card.Title>
                  {recipe.comments.length > 0 ? (
                    <ul>
                      {recipe.comments.map((comment, index) => (
                        <li key={index}>
                          {comment.content}
                          {(isCreator || isAdmin) && (
                            <Button className='x-btn' onClick={() => handleDeleteComment(comment.id)}>Delete</Button>
                          )}
                        </li>
                      ))}
                    </ul>
                  ) : (
                    <div>No comments yet</div>
                  )}
                </Card.Body>
              </Card>
              <Card className="mt-4">
                <Card.Body>
                  <Card.Title >Add Comment</Card.Title>
                  <Form onSubmit={handleSubmitComment}>
                        <Form.Group controlId="commentContent">
                            <Form.Control
                            type="text"
                            placeholder="Enter your comment"
                            value={commentContent}
                            onChange={handleChange}
                            />
                        </Form.Group>
                        <Button className='action-btn mt-2' variant="primary" type="submit">Add Comment</Button>
                        </Form>
                </Card.Body>
              </Card>
              <div className="mt-4">
                {(isCreator || isAdmin) && (
                  <>
                    <Button className='action-btn' variant="primary" onClick={handleEditRecipe}>Edit Recipe</Button>
                    <Button className='action-btn margin-l3' variant="danger" onClick={handleDeleteRecipe}>Delete Recipe</Button>
                  </>
                )}
              </div>
            </div>
          </div>

          {editMode && (
            <div className="mt-4">
              <h2>Edit Recipe</h2>
              <Form>
                <Form.Group controlId="editName">
                  <Form.Label>Name</Form.Label>
                  <Form.Control
                    type="text"
                    placeholder="Enter recipe name"
                    value={editedRecipe.name}
                    onChange={(e) => setEditedRecipe({ ...editedRecipe, name: e.target.value })}
                  />
                </Form.Group>
                <Form.Group controlId="editDescription">
                  <Form.Label>Description</Form.Label>
                  <Form.Control
                    as="textarea"
                    rows={3}
                    placeholder="Enter recipe description"
                    value={editedRecipe.description}
                    onChange={(e) => setEditedRecipe({ ...editedRecipe, description: e.target.value })}
                  />
                </Form.Group>
                <Button className="mr-2" variant="primary" onClick={handleSaveEdit}>Save</Button>
                <Button variant="secondary" onClick={handleCancelEdit}>Cancel</Button>
              </Form>
            </div>
          )}
        </>
      )}
    </div>
  );
}

export default RecipeDetail;
                   
