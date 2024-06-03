import React, { useState, useEffect } from 'react';
import axios from './axiosConfig';
import { Link, useNavigate } from 'react-router-dom';
import { Form, Button } from 'react-bootstrap';
import useAuth from './useAuth';
import './assets/styles/RecipeAddComponent.css';

function AddRecipeForm() {
  useAuth();
  const navigate = useNavigate();
  const [recipeData, setRecipeData] = useState({
    name: '',
    description: '',
    typeOfCuisine: '',
    ingredients: [{ ingredient: '', quantity: '', unit: '' }],
  });
  const [units, setUnits] = useState([]);
  const [typesOfCuisine, setTypesOfCuisine] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchUnits = async () => {
      try {
        const response = await axios.get('https://localhost:8000/api/units');
        setUnits(response.data['hydra:member']);
      } catch (error) {
        console.error('Error fetching units:', error);
        setError('Error fetching units. Please try again.');
      }
    };

    const fetchTypesOfCuisine = async () => {
      try {
        const response = await axios.get('https://localhost:8000/api/type_of_cuisines');
        setTypesOfCuisine(response.data['hydra:member']);
      } catch (error) {
        console.error('Error fetching types of cuisine:', error);
        setError('Error fetching types of cuisine. Please try again.');
      }
    };

    fetchUnits();
    fetchTypesOfCuisine();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setRecipeData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  const handleIngredientChange = (index, e) => {
    const { name, value } = e.target;
    const ingredients = [...recipeData.ingredients];
    ingredients[index][name] = value;
    setRecipeData((prevData) => ({
      ...prevData,
      ingredients,
    }));
  };

  const handleAddIngredient = () => {
    setRecipeData((prevData) => ({
      ...prevData,
      ingredients: [...prevData.ingredients, { ingredient: '', quantity: '', unit: '' }],
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const user_id = localStorage.getItem('user_id');
      if (!user_id) {
        console.error('User ID not found in local storage');
        return;
      }

      const requestData = {
        ...recipeData,
        creator_id: user_id,
      };

      const response = await axios.post(
        'https://localhost:8000/recipes',
        JSON.stringify(requestData),
        {
          headers: {
            'Content-Type': 'application/ld+json',
          },
        }
      );
      console.log('Recipe created:', response.data);

      navigate('/dashboard');
    } catch (error) {
      console.error('Error creating recipe:', error);
      setError('Error creating recipe. Please try again.');
    }
  };

  return (
    <AddRecipe
      typesOfCuisine={typesOfCuisine}
      units={units}
      handleSubmit={handleSubmit}
      handleChange={handleChange}
      handleIngredientChange={handleIngredientChange}
      handleAddIngredient={handleAddIngredient}
      recipeData={recipeData}
      error={error}
    />
  );
}

function AddRecipe({ typesOfCuisine, units, handleSubmit, handleChange, handleIngredientChange, handleAddIngredient, recipeData, error }) {
  const handleRemoveIngredient = (index) => {
    const newIngredients = recipeData.ingredients.filter((_, i) => i !== index);
    handleChange({ target: { name: 'ingredients', value: newIngredients } });
  };

  return (
    <div className="container-edit">
      <div className="d-flex flex-row space-between">
        <h2 className='add-recipe-header'>Add New Recipe</h2>
        <Link to="/dashboard" className="ml-4 btn btn-lg btn-success add-recipe-button">Return</Link>
      </div>
      {error && 
        <div className="alert alert-danger mt-1" role="alert">
          {error}
        </div>
      }
      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="name">
          <Form.Label>Name</Form.Label>
          <Form.Control type="text" name="name" value={recipeData.name} onChange={handleChange} required />
        </Form.Group>
        <Form.Group controlId="description">
          <Form.Label>Description</Form.Label>
          <Form.Control as="textarea" rows={3} name="description" value={recipeData.description} onChange={handleChange} required />
        </Form.Group>
        <Form.Group controlId="typeOfCuisine">
          <Form.Label>Type of Cuisine</Form.Label>
          <Form.Control as="select" name="typeOfCuisine" value={recipeData.typeOfCuisine} onChange={handleChange} required>
            <option value="">Select Type of Cuisine</option>
            {typesOfCuisine.map((cuisine) => (
              <option key={cuisine.id} value={cuisine.id}>{cuisine.name}</option>
            ))}
          </Form.Control>
        </Form.Group>
        <Form.Group controlId="ingredients" className='mb-3' required>
          <Form.Label>Ingredients</Form.Label>
          {recipeData.ingredients.map((ingredient, index) => (
            <div className='my-4 d-flex align-items-center' key={index}>
              <div className='flex-grow-1'>
                <Form.Control type="text" name="ingredient" className='my-1' placeholder="Ingredient" value={ingredient.ingredient} onChange={(e) => handleIngredientChange(index, e)} />
                <Form.Control type="number" name="quantity" className='my-1' placeholder="Quantity" value={ingredient.quantity} onChange={(e) => handleIngredientChange(index, e)} />
                <Form.Control as="select" name="unit" className='my-1' value={ingredient.unit} onChange={(e) => handleIngredientChange(index, e)}>
                  <option value="">Select Unit</option>
                  {units.map((unit) => (
                    <option key={unit.id} value={unit.id}>{unit.name}</option>
                  ))}
                </Form.Control>
              </div>
              <Button variant="danger" className='ml-3 delete-recipe-ingredient-button' onClick={() => handleRemoveIngredient(index)}>X</Button>
            </div>
          ))}
          <Button className='add-ingredient-button' variant="secondary" onClick={handleAddIngredient}>Add Ingredient</Button>
        </Form.Group>
        <Button className='submit-recipe button mb-3 ml-0' variant="primary" type="submit">Submit</Button>
      </Form>
    </div>
  );
}

export default AddRecipeForm;
