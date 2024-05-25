import React from 'react';
import './assets/styles/Navbar.css';

function Navbar() {
  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <a className="navbar-brand website-name" href="">RecipeRadar</a>
      <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span className="navbar-toggler-icon"></span>
      </button>
      <div className="collapse navbar-collapse" id="navbarNav">
          <ul className="navbar-nav ml-auto">
              <li className="nav-item">
                  <a className="nav-link nav-text" href="">Contact</a>
              </li>
              <li className="nav-item">
                  <a className="nav-link nav-text" href="">About Us</a>
              </li>
          </ul>
      </div>
    </nav>
  );
}

export default Navbar;