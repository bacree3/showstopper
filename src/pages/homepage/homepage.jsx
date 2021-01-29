import React from 'react';


import './homepage-style.css';

const HomePage = () => (
    <div className='homepage'>
        <form className='form'>
            <input type='text' value='Email'/>
            <input type='password' value='Password' />
            <input type='submit' value='Log in' />
        </form>
        <div className='content'>
            <h1>Showstopper</h1>
            <h3>Get the most out of your streaming services</h3>
            <p>Create an account today</p>
            <button>Register</button>
        </div>
    </div>
);

export default HomePage;