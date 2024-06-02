import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Card, Button, Form } from 'react-bootstrap';
import { useParams, useNavigate, Link } from 'react-router-dom';
import useAuth from './useAuth';
import './assets/styles/RecipeDetail.css';

function RecipeDetail({ isAuthenticated }) {
  useAuth(isAuthenticated);
  const { id } = useParams();
  const navigate = useNavigate();
  const [recipe, setRecipe] = useState(null);
  const [loading, setLoading] = useState(true);
  const [commentContent, setCommentContent] = useState('');
  const [isFollowed, setIsFollowed] = useState(false);

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

  useEffect(() => {
    const followedRecipes = JSON.parse(localStorage.getItem('followed_recipes')) || [];
    setIsFollowed(followedRecipes.includes(parseInt(id)));
  }, [id]);

  const handleChange = (e) => {
    setCommentContent(e.target.value);
  };

  const handleDeleteRecipe = async () => {
    try {
      await axios.delete(`https://localhost:8000/api/recipes/${id}`);
      
      navigate('/dashboard');
    } catch (error) {
      console.error('Error deleting recipe:', error);
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

  const handleFollowRecipe = async (recipeId) => {
    const userId = localStorage.getItem('user_id');
    try {
      await axios.post(
        `https://localhost:8000/api/users/${userId}/recipes/${recipeId}`,
        {},
        {
          headers: {
            'Content-Type': 'application/ld+json',
          },
        }
      );

      let followedRecipes = JSON.parse(localStorage.getItem('followed_recipes')) || [];
      if (!followedRecipes.includes(recipeId)) {
        followedRecipes.push(recipeId);
        localStorage.setItem('followed_recipes', JSON.stringify(followedRecipes));
        setIsFollowed(true);
      }

      console.log('Recipe followed successfully');
    } catch (error) {
      console.error('Error following recipe:', error);
    }
  };

  const handleUnfollowRecipe = async (recipeId) => {
    const userId = localStorage.getItem('user_id');

    try {
      await axios.delete(
        `https://localhost:8000/api/users/${userId}/recipes/${recipeId}`,
        {
          headers: {
            'Content-Type': 'application/ld+json',
          },
        }
      );

      let followedRecipes = JSON.parse(localStorage.getItem('followed_recipes')) || [];
      followedRecipes = followedRecipes.filter(id => id !== recipeId);
      localStorage.setItem('followed_recipes', JSON.stringify(followedRecipes));
      setIsFollowed(false);

      console.log('Recipe unfollowed successfully');
    } catch (error) {
      console.error('Error unfollowing recipe:', error);
    }
  };

  if (loading || !recipe) {
    return <div>Loading...</div>;
  }

  const user_id = localStorage.getItem('user_id');
  const user_role = localStorage.getItem('user_role');
  const creatorId = recipe.creator.id;
  const isCreator = parseInt(user_id) === parseInt(creatorId);
  const isAdmin = user_role.includes('ADMIN');

  return (
    <div className="container mt-4">
      {recipe && (
        <>
          <div className="d-flex justify-content-between align-items-center mb-4">
            <h1>{recipe.name}</h1>
            {isFollowed ? (
              <Link
                to="#"
                className="btn btn-sm btn-follow btn-danger btn-unfollow"
                onClick={(e) => {
                  e.preventDefault();
                  handleUnfollowRecipe(recipe.id);
                }}
              >
                <span className="material-symbols-outlined">heart_broken</span>
              </Link>
            ) : (
              <Link
                to="#"
                className="btn btn-warning btn-sm btn-follow"
                onClick={(e) => {
                  e.preventDefault();
                  handleFollowRecipe(recipe.id);
                }}
              >
                <span className="material-symbols-outlined">favorite</span>
              </Link>
            )}
          </div>
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
                          <strong>{comment.creator.email}</strong>: {comment.content}
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
                  <Card.Title>Add Comment</Card.Title>
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
            </div>
          </div>
          <div className="mt-4">
            {(isCreator || isAdmin) && (
              <>
                <Button className='action-btn' variant="primary">Edit Recipe</Button>
                <Button className='action-btn margin-l3' variant="danger" onClick={handleDeleteRecipe}>Delete Recipe</Button>
              </>
            )}
          </div>
        </>
      )}
    </div>
  );
}

export default RecipeDetail;
