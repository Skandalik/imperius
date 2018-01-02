import React from 'react';
import { Link } from 'react-router-dom';

class Header extends React.Component {
  render() {
    return (
      <header>
        <nav className="navbar navbar-inverse">
          <div className="container-fluid">
            <div className="navbar-header">
              <button
                type="button"
                className="navbar-toggle collapsed"
                data-toggle="collapse"
                data-target="#navbar"
                aria-expanded="false"
                aria-controls="navbar"
              >
                <span className="sr-only">Toggle navigation</span>
                <span className="icon-bar" />
                <span className="icon-bar" />
                <span className="icon-bar" />
              </button>
              <a className="navbar-brand" href="/">
                Imperius
              </a>
            </div>
            <div id="navbar" className="navbar-collapse collapse navbar-right">
              <ul className="nav navbar-nav">
                <li>
                  <Link to="/overview"> Overview </Link>
                </li>
                <li>
                  <Link to="/sensor"> Sensor </Link>
                </li>
                <li>
                  <Link to="/room"> Room </Link>
                </li>
                <li>
                  <Link to="/logout"> Logout </Link>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </header>
    );
  }
}

export default Header;
