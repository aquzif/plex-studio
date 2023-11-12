import React from 'react';
import { createRoot } from 'react-dom/client'
import App from "@/App/App.jsx";

//add devlog function to console
console.devlog = function(...args){
    if(window.location.host.includes('localhost')){
        console.log(...args)
    }
}


if(document.getElementById('root')){
    createRoot(document.getElementById('root')).render(<App />)
}

