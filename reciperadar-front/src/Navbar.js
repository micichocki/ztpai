import React from 'react';
import { Link } from 'react-router-dom';
import './assets/styles/Navbar.css';

function Navbar({ isAuthenticated }) {
  const userId = localStorage.getItem("userId");
  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <Link className="navbar-brand website-name" to="/dashboard">RecipeRadar</Link>
      <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span className="navbar-toggler-icon"></span>
      </button>
      <div className="collapse navbar-collapse" id="navbarNav">
        <ul className="navbar-nav ml-auto">
          {isAuthenticated && (
            <li className="nav-item">
              <Link className="nav-link nav-text" to={`/profile/${userId}`}>Profile</Link>
            </li>
          )}
          {isAuthenticated && (
            <li className="nav-item">
              <Link className="nav-link nav-text" to="/logout">Logout</Link>
            </li>
          )}
          <li className="nav-item">
            <a className="nav-link nav-text" href="https://github.com/micichocki">Contact</a>
          </li>
          <li className="nav-item">
            <a className="nav-link nav-text" href="https://github.com/micichocki">About Us</a>
          </li>
        </ul>
      </div>
    </nav>
  );
}

export default Navbar;
