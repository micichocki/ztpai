import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';

function RecipeView() {
  const [recipes, setRecipes] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchRecipes = async () => {
      try {
        const response = await axios.get('https://localhost:8000/api/recipes');
        const recipes = response.data['hydra:member'];
        setRecipes(recipes);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching recipes:', error);
        setLoading(false);
      }
    };

    fetchRecipes();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  return (
    <div>
      <h2>All Recipes</h2>
      {Array.isArray(recipes) && recipes.length > 0 ? (
        <ul>
          {recipes.map(recipe => (
            <li key={recipe.id}>
              <h3>{recipe.name}</h3>
              <p>{recipe.description}</p>
            </li>
          ))}
        </ul>
      ) : (
        <div>No recipes found</div>
      )}
    </div>
  );
}

export default RecipeView;
