import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Link } from 'react-router-dom';
import { Card, Button } from 'react-bootstrap';
import './assets/styles/Dashboard.css';
import useAuth from './useAuth';
import { useLocation } from 'react-router-dom';
import { Alert } from 'react-bootstrap';

function RecipeView() {
  useAuth();

  const [recipes, setRecipes] = useState([]);
  const [filteredRecipes, setFilteredRecipes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCuisine, setSelectedCuisine] = useState('');
  const [followedRecipes, setFollowedRecipes] = useState([]); 
  const location = useLocation(); 
  const message = location.state && location.state.message; 

  useEffect(() => {
    const fetchRecipes = async () => {
      try {
        const response = await axios.get('https://localhost:8000/api/recipes?include=typeOfCuisine');
        const recipesData = response.data;

        setRecipes(recipesData);
        setFilteredRecipes(recipesData);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching recipes:', error);
        setLoading(false);
      }
    };

    fetchRecipes();
  }, []);

  useEffect(() => {
    const storedFollowedRecipes = JSON.parse(localStorage.getItem('followed_recipes')) || [];
    setFollowedRecipes(storedFollowedRecipes);
  }, []);

  function truncateDescription(description, maxLength) {
    if (description.length <= maxLength) {
      return description;
    } else {
      return description.substring(0, maxLength) + "...";
    }
  }
  

  useEffect(() => {
    const results = recipes.filter(recipe =>
      recipe.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      recipe.typeOfCuisine.name.toLowerCase().includes(searchQuery.toLowerCase())
    );

    const filteredByCuisine = selectedCuisine
      ? results.filter(recipe => recipe.typeOfCuisine.id === selectedCuisine)
      : results;

    setFilteredRecipes(filteredByCuisine);
  }, [searchQuery, selectedCuisine, recipes]);

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

      const updatedFollowedRecipes = [...followedRecipes, recipeId];
      setFollowedRecipes(updatedFollowedRecipes);
      localStorage.setItem('followed_recipes', JSON.stringify(updatedFollowedRecipes));
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
  
      const updatedFollowedRecipes = followedRecipes.filter(id => id !== recipeId);
      setFollowedRecipes(updatedFollowedRecipes);
      localStorage.setItem('followed_recipes', JSON.stringify(updatedFollowedRecipes));
      console.log('Recipe unfollowed successfully');
    } catch (error) {
      console.error('Error unfollowing recipe:', error);
    }
  };
  
  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container2">
      {message && (
        <Alert variant="success">
          {message}
        </Alert>
      )}
      
      <div className="text-center mt-4 mb-4 d-flex flex-row justify-content-center align-items-center sub-bar">
        <h2 className='h2-recipe-radar'>All Recipes</h2>
        <Link to="/add-recipe" className="ml-2 btn btn-success add-recipe-button2">+</Link>
        <input
          type="text"
          placeholder="Search recipes..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="ml-2 form-control search-bar search-bar-component min-width-100"
        />
      </div>
      <div className="row">
        {filteredRecipes.map(recipe => {
          const isFollowed = followedRecipes.includes(recipe.id);

          return (
            <div key={recipe.id} className="col-md-4 mb-4">
              <Card>
                <Card.Body>
                  <Card.Title>{recipe.name}</Card.Title>
                  <Card.Text>{truncateDescription(recipe.description, 100)}</Card.Text>
                  {recipe.ingredients.length > 0 && (
                    <Card.Text>
                      <strong>Ingredients:</strong> {recipe.ingredients.map(ingredient => (
                        <span key={ingredient.id}>{ingredient.name}, </span>
                      ))}
                    </Card.Text>
                  )}
                  <Card.Text>
                    <strong>Type of Cuisine:</strong> {recipe.typeOfCuisine.name}
                  </Card.Text>
                  <div className="d-flex justify-content-between">
                    <Link to={`/recipe/${recipe.id}`} className="btn btn-primary btn-lg btn-recipe-detail">View Details</Link>
                    {isFollowed ? (
                      <Link
                        to="#"
                        className="btn  btn-sm btn-follow btn-danger btn-unfollow"
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
                </Card.Body>
              </Card>
            </div>
          );
        })}
      </div>
    </div>
  );
}

export default RecipeView;
