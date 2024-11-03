import React, { useState } from 'react';
import NouvelleImage from './assets/imgs/news1.jpg'


const Nouveautes = () => {

    return <div>
        <img src={NouvelleImage} alt="Nouveauté!"/>
        <h2>Ce mois-ci :</h2>
        <div>De nouveaux croiseurs sont disponibles en avant première. Venez découvrir le FX-500 et le M.A.N.O.R pour dominer l'espace!</div>

    </div>
}


export default Nouveautes;