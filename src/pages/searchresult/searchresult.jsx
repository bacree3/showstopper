import React from 'react';


import './searchresult-style.css';
import logo from './avengers.jpg';

const SearchResult = () => (
    <div className='searchcontainer'>

    	<form className = 'form'>
    		<input type = 'text' value= 'search'/>
    		<input type = 'submit' value = 'search'/>
    	</form>

    	<div className = 'resultscon'>

        	<div className = 'resultitem'>

        		<div className='movieimage'>
    				<img src = {logo} alt = "Logo"/>
    			</div>

    			<div className='itemdetails'>
    				<h2>Movie Name</h2>
    				<p>Summary:</p>
    				<p>Cast:</p>
    			</div>
			</div>

			<div className = 'resultitem'>

        		<div className='movieimage'>
    				<img src = {logo} alt = "Logo"/>
    			</div>

    			<div className='itemdetails'>
    				<h2>Movie Name</h2>
    				<p>Summary:</p>
    				<p>Cast:</p>
    			</div>
			</div>

			<div className = 'resultitem'>

        		<div className='movieimage'>
    				<img src = {logo} alt = "Logo"/>
    			</div>

    			<div className='itemdetails'>
    				<h2>Movie Name</h2>
    				<p>Summary:</p>
    				<p>Cast:</p>
    			</div>
			</div>



		</div>
    </div>
);

export default SearchResult;