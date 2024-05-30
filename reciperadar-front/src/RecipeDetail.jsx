import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Card, Button, Form } from 'react-bootstrap';
import { useParams } from 'react-router-dom';
import useAuth from './useAuth';
import './assets/styles/RecipeDetail.css'
import { useNavigate } from 'react-router-dom'; // Import useNavigate

function RecipeDetail({ isAuthenticated }) {
  useAuth(isAuthenticated);
  const { id } = useParams();
  const navigate = useNavigate(); // Initialize useNavigate
  const [recipe, setRecipe] = useState(null);
  const [loading, setLoading] = useState(true);
  const [commentContent, setCommentContent] = useState('');
  const [currentUser, setCurrentUser] = useState(null);

  useEffect(() => {
    const fetchRecipe = async () => {
      try {
        const recipeResponse = await axios.get(`https://localhost:8000/api/recipes/${id}`);
        const recipeData = recipeResponse.data;

        const typeOfCuisineUrl = recipeData.typeOfCuisine;
        const typeOfCuisineResponse = await axios.get(`https://localhost:8000`.concat(typeOfCuisineUrl));
        const typeOfCuisineData = typeOfCuisineResponse.data;
        const typeName = typeOfCuisineData.name;

        const recipeWithTypeName = { ...recipeData, typeOfCuisineName: typeName };

        setRecipe(recipeWithTypeName);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching recipe:', error);
        setLoading(false);
      }
    };

    const fetchCurrentUser = async () => {
      try {
        const userId = localStorage.getItem("userId");
        const userResponse = await axios.get(`https://localhost:8000/api/users/${userId}`);
        console.log(userResponse.data);
        setCurrentUser(userResponse.data);
      } catch (error) {
        console.error('Error fetching current user:', error);
      }
    };

    fetchRecipe();
    fetchCurrentUser();
  }, [id]);

  const handleChange = (e) => {
    setCommentContent(e.target.value);
  };

  const handleSubmitComment = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(`https://localhost:8000/api/recipes/${id}/comments`, {
        content: commentContent,
      });
      console.log('Comment added:', response.data);
      const updatedResponse = await axios.get(`https://localhost:8000/api/recipes/${id}`);
      const updatedRecipeData = updatedResponse.data;
      setRecipe(updatedRecipeData);
      setCommentContent('');
    } catch (error) {
      console.error('Error adding comment:', error);
    }
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

  if (loading || !currentUser) {
    return <div>Loading...</div>;
  }

  const creatorId = parseInt(recipe.creator.match(/\d+/)[0]);
  const isCreator = currentUser.id === creatorId;
  const isAdmin = currentUser.roles.includes('admin');

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
                    {recipe.ingredients.map(ingredient => (
                      <li key={ingredient.id}>{ingredient.name}</li>
                    ))}
                  </ul>
                </Card.Body>
              </Card>
            </div>
            <div className="col-md-4">
              <Card className='conditional-mt-3'>
                <Card.Body>
                  <Card.Title>Type of Cuisine</Card.Title>
                  <Card.Text>{recipe.typeOfCuisineName}</Card.Text>
                </Card.Body>
              </Card>
              <Card className="mt-4">
                <Card.Body>
                  <Card.Title>Comments</Card.Title>
                  {recipe.comments.length > 0 ? (
                    <ul>
                      {recipe.comments.map(comment => (
                        <li key={comment.id}>{comment.content}</li>
                      ))}
                    </ul>
                  ) : (
                    <div>No comments yet</div>
                  )}
                </Card.Body>
              </Card>
              <Card className="mt-4">
                <Card.Body>
                  <Card.Title>Add Comment</Card.Title>
                  <Form onSubmit={handleSubmitComment}>
                    <Form.Group controlId="commentContent">
                      <Form.Control type="text" placeholder="Enter your comment" value={commentContent} onChange={handleChange} />
                    </Form.Group>
                    <Button  variant="primary" type="submit" className='btn-recipe-detail'>Add Comment</Button>
                  </Form>
                </Card.Body>
              </Card>
            </div>
          </div>
          <div className="mt-4">
            {(isCreator || isAdmin) && (
              <>
                <Button className='btn-recipe-detail' variant="primary">Edit Recipe</Button>
                <Button className='btn-recipe-detail' variant="danger btn-delete" onClick={handleDeleteRecipe}>Delete Recipe</Button>
              </>
            )}
          </div>
        </>
      )}
    </div>
  );
}

export default RecipeDetail;
