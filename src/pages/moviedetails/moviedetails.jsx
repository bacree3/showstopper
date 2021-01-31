import React from 'react';


import './moviedetails-style.css';
import logo from './avengers.jpg';

const MovieDetails = () => (
	<div className = 'moviecontainer'>

    	<div className='movieimage'>
    		<img src = {logo} alt = "Logo"/>
    	</div>

    	<div className='moviedetails'>
    		<h1>Movie Name</h1>
    		<p>Release Date</p>
    		<p>Rating</p>
    		<p>Cast</p>
    		<p>Summary</p>
    	</div>
    	
    </div>
);

export default MovieDetails;