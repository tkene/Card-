import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  withCredentials: true, // Important pour les sessions Symfony
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Intercepteur pour gérer les erreurs
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response) {
      // Erreur de réponse du serveur
      console.error('Erreur API:', error.response.data)
    } else if (error.request) {
      // Requête envoyée mais pas de réponse
      console.error('Pas de réponse du serveur')
    } else {
      // Erreur lors de la configuration de la requête
      console.error('Erreur:', error.message)
    }
    return Promise.reject(error)
  }
)

export default api

