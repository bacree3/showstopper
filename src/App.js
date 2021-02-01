import React from 'react';
import {Switch, Route} from 'react-router-dom';

import HomePage from './pages/homepage/homepage';
import ActorDetails from './pages/actordetails/actordetails';
import MovieDetails	 from './pages/moviedetails/moviedetails';
import SearchResult from './pages/searchresult/searchresult';


import './App.css';

function App() {
  return (
    <Switch>
      <Route exact path='/' component={HomePage} />
    </Switch>
  );
}

export default App;
