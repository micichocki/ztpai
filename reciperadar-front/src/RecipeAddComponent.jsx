import React, { useState } from 'react';
import axios from './axiosConfig';
import { Link } from 'react-router-dom';
import { Form, Button } from 'react-bootstrap';
import useAuth from './useAuth';

function AddRecipeForm() {
  useAuth();
  const [recipeData, setRecipeData] = useState({
    name: '',
    description: '',
    typeOfCuisine: '',
    ingredients: [{ ingredient: '', quantity: '', unit: '' }],
  });

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
      const response = await axios.post('https://localhost:8000/api/recipes', recipeData);
      console.log('Recipe created:', response.data);
    } catch (error) {
      console.error('Error creating recipe:', error);
    }
  };

  return (
    <div className="container">
      <div class="d-flex flex-row justify-content-center">
      <h2>Add New Recipe</h2>
      <Link to="/dashboard" className="ml-2 btn btn-lg btn-success add-recipe-button">Return</Link>
      </div>
      <Form onSubmit={handleSubmit}>
        <Form.Group controlId="name">
          <Form.Label>Name</Form.Label>
          <Form.Control type="text" name="name" value={recipeData.name} onChange={handleChange} />
        </Form.Group>
        <Form.Group controlId="description">
          <Form.Label>Description</Form.Label>
          <Form.Control as="textarea" rows={3} name="description" value={recipeData.description} onChange={handleChange} />
        </Form.Group>
        <Form.Group controlId="ingredients">
          <Form.Label>Ingredients</Form.Label>
          
          {recipeData.ingredients.map((ingredient, index) => (
            <div class='my-4'>
            <div key={index}>
              <Form.Control type="text" name="ingredient" className='my-1' placeholder="Ingredient" value={ingredient.ingredient} onChange={(e) => handleIngredientChange(index, e)} />
              <Form.Control type="number" name="quantity" className='my-1' placeholder="Quantity" value={ingredient.quantity} onChange={(e) => handleIngredientChange(index, e)} />
              <Form.Control as="select" name="unit" className='my-1' value={ingredient.unit} onChange={(e) => handleIngredientChange(index, e)}>
                <option value="">Select Unit</option>
              </Form.Control>
            </div>
            </div>
          ))}
          
          <Button variant="secondary" onClick={handleAddIngredient}>Add Ingredient</Button>
        </Form.Group>
        <Button variant="primary" type="submit">Submit</Button>
      </Form>
    </div>
  );
}

export default AddRecipeForm;
