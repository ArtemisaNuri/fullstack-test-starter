import React, { Component } from 'react';
import { NavLink } from 'react-router-dom';
import styles from './Header.module.css'; 

class Header extends Component {
  render() {
    return (
      <header className={styles.header}>
        <nav className={styles.nav}>
          <div className={styles.centerIcon}>
            <h1 className="text-xl font-bold">E-Commerce</h1>
          </div>

          <ul className={styles.navLinks}>
            <li>
              <NavLink
                exact
                to="/"
                className={styles.navLink}
                activeClassName={styles.activeNavLink}
                isActive={(match, location) => location.pathname === '/'}
                data-testid="category-link"
              >
                All
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/tech"
                className={styles.navLink}
                activeClassName={styles.activeNavLink}
                data-testid="category-link"
              >
                Tech
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/clothes"
                className={styles.navLink}
                activeClassName={styles.activeNavLink}
                data-testid="category-link"
              >
                Clothes
              </NavLink>
            </li>
          </ul>

          <div className={styles.cartContainer}>
            <button className={styles.cartBtn} data-testid="cart-btn">
              <i className="fas fa-shopping-cart"></i>
              <span className={styles.cartBubble}>3</span>
            </button>
          </div>
        </nav>
      </header>
    );
  }
}

export default Header;
