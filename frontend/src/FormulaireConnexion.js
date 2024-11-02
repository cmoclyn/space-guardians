// src/FormulaireConnexion.js
import React, { useState } from 'react';

export default function FormulaireConnexion() {
    const [formData, setFormData] = useState(
        {
            username: '',
            password: '',
        }
    )

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log('Essai de connexion :', formData);
        // ici tu pourras ajouter une logique pour envoyer les données vers ton backend Symfony
    };

    return (
        <form onSubmit={handleSubmit}>
            <label>
                Nom d'utilisateur:
                <input
                    type="text"
                    name="username"
                    value={formData.username}
                    onChange={handleChange}
                    required
                />
            </label>
            <label>
                Mot de passe:
                <input
                    type="password"
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    required
                />
            </label>
            <button type="submit">Se connecter</button>
        </form>
    );



}







