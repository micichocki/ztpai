import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Link } from 'react-router-dom';
import { Card } from 'react-bootstrap';
import './assets/styles/Dashboard.css';
import useAuth from './useAuth';

function RecipeView() {
  useAuth();


  const [recipes, setRecipes] = useState([]);
  const [filteredRecipes, setFilteredRecipes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    const fetchRecipes = async () => {
      try {
        const response = await axios.get('https://localhost:8000/api/recipes');
        const recipesData = response.data['hydra:member'];
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
    const results = recipes.filter(recipe =>
      recipe.name.toLowerCase().includes(searchQuery.toLowerCase())
    );
    setFilteredRecipes(results);
  }, [searchQuery, recipes]);

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container">
      <div className="text-center mt-4 mb-4 d-flex flex-row justify-content-center align-items-center sub-bar">
        <h2>All Recipes</h2>
        <Link to="/add-recipe" className="ml-2 btn btn-success add-recipe-button">+</Link>
        <input
          type="text"
          placeholder="Search recipes..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="ml-2 form-control search-bar search-bar-component"
        />
      </div>
      <div className="row">
        {filteredRecipes.map(recipe => (
          <div key={recipe.id} className="col-md-4 mb-4">
            <Card>
              <Card.Body>
                <Card.Title>{recipe.name}</Card.Title>
                <Card.Text>{recipe.description}</Card.Text>
                <Card.Text>
                  <strong>Type of Cuisine:</strong> {recipe.typeOfCuisine.name}
                </Card.Text>
                <Card.Text>
                  <strong>Ingredients:</strong>
                  <ul>
                    {recipe.ingredients.map(ingredient => (
                      <li key={ingredient.id}>{ingredient.name}</li>
                    ))}
                  </ul>
                </Card.Text>
                <Link to={`/recipe/${recipe.id}`} className="btn btn-primary btn-lg btn-recipe-detail">View Details</Link>
              </Card.Body>
            </Card>
          </div>
        ))}
      </div>
    </div>
  );
}

export default RecipeView;
