import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Card, Button } from 'react-bootstrap';
import './assets/styles/Profile.css'
import { Link } from 'react-router-dom';
import useAuth from './useAuth';

function Profile() {
  useAuth();
  const [userData, setUserData] = useState({
    name: '',
    surname: '',
  });
  const [followedRecipes, setFollowedRecipes] = useState([]);
  const [loading, setLoading] = useState(true);

  
  const handleSubmit = async (e) => {
    e.preventDefault();
    const userId = localStorage.getItem('userId');
    const uc_id = localStorage.getItem('uc_id');
    if (!uc_id) {
      console.error('User Credentials ID not found in local storage');
      return;
    }
  
    try {
      await axios.put(`https://localhost:8000/api/user_credentials/${uc_id}`, userData, {
        headers: {
          'Content-Type': 'application/ld+json',
        },
      });
      console.log('User data updated successfully');
      localStorage.setItem('name', userData.name);
      localStorage.setItem('surname', userData.surname);
      window.location.reload();
    } catch (error) {
      console.error('Error updating user data:', error);
    }
  };

  useEffect(() => {
    const name = localStorage.getItem('name') || '';
    const surname = localStorage.getItem('surname') || '';

    setUserData({ name, surname });
  }, []);

  const handleChange = (e) => {
    setUserData({
      ...userData,
      [e.target.name]: e.target.value
    });

    localStorage.setItem(e.target.name, e.target.value);
  };

  useEffect(() => {
    const fetchFollowedRecipes = async () => {
      try {
        const followedRecipeIds = JSON.parse(localStorage.getItem('followed_recipes')) || [];

        const promises = followedRecipeIds.map(async (recipeId) => {
          const response = await axios.get(`https://localhost:8000/api/recipes/${recipeId}`);
          return response.data;
        });

        const recipes = await Promise.all(promises);
        setFollowedRecipes(recipes);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching followed recipes:', error);
        setLoading(false);
      }
    };

    fetchFollowedRecipes();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }
 let followers_count = localStorage.getItem('followers_count');

  return (
    <main>
<div>
<div>
      
        <div className="settings-nav">
          <ul className="settings-buttons">
            <li className='setings-button'><a href="">Personal Info</a></li>
            <li className='setings-button'><a href="">Settings</a></li>
            {/* <li className='setings-button'>Followers count: {followers_count}</li> */}
          </ul>
        </div>

        <div className="personal-info-container">
          <div className="welcome-text-container">
            <h1 className="welcome-text">Please provide your credentials</h1>
          </div>

          <form className="personal-info-form" onSubmit={handleSubmit}>
            <label htmlFor="name">Name *</label>
            <input
              className='input'
              id="name"
              type="text"
              name="name"
              placeholder="Enter your name"
              value={userData.name}
              onChange={handleChange}
            />

            <label htmlFor="surname">Surname *</label>
            <input
              className='input'
              id="surname"
              type="text"
              name="surname"
              maxLength="125"
              placeholder="Enter your surname"
              value={userData.surname}
              onChange={handleChange}
            />

            <Button variant="secondary" type="submit">Submit</Button>
          </form>
        </div>
     
    </div>
    <div>
      <h1 className='text-center'>Followed Recipes</h1>
      <div className="row bottom-profile-side">
        {followedRecipes.map(recipe => (
          <div key={recipe.id} className="col-md-4 mb-4 ">
            <Card>
              <Card.Body>
                <Card.Title>{recipe.name}</Card.Title>
                <Card.Text>{recipe.description}</Card.Text>
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
                <Link to={`/recipe/${recipe.id}`} className="btn btn-primary btn-lg btn-recipe-detail">View Details</Link>
              </Card.Body>
            </Card>
          </div>
        ))}
      </div>
    </div>
    </div>
    </main>
  );
}

export default Profile;
