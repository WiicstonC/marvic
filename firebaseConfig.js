// firebaseConfig.js
// Importa las funciones necesarias (estilo modular de Firebase v9+)
import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider, OAuthProvider } from "firebase/auth";
import { getFirestore } from "firebase/firestore";
import { getAnalytics } from "firebase/analytics";

// Tu objeto de configuración obtenido desde la consola de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyBvreJCnnkDQEaxu8m-uufg5tiyMmGUCVw",
    authDomain: "marvic-b8842.firebaseapp.com",
    projectId: "marvic-b8842",
    storageBucket: "marvic-b8842.firebasestorage.app",
    messagingSenderId: "341444282988",
    appId: "1:341444282988:web:2b79f1c21c95397775a649",
    measurementId: "G-KQEYV4M86K"
  };

// Inicializa Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

// Inicializa servicios
const auth = getAuth(app);
const db = getFirestore(app);

// Proveedores de autenticación
const googleProvider = new GoogleAuthProvider();
// Para Microsoft (Hotmail), utiliza el OAuthProvider con el id "microsoft.com"
const microsoftProvider = new OAuthProvider('microsoft.com');
// Para Apple, utiliza el OAuthProvider con el id "apple.com"
const appleProvider = new OAuthProvider('apple.com');

export { auth, db, googleProvider, microsoftProvider, appleProvider };
