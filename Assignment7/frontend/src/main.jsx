import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.jsx'
import './index.css'
import { Toaster } from 'react-hot-toast'

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <App />
    <Toaster
      position="bottom-right"
      toastOptions={{
        duration: 3000,
        style: {
          background: '#0a0f1e',
          color: '#f1f5f9',
          border: '1px solid rgba(255,255,255,0.08)',
          borderRadius: '10px',
          fontSize: '0.82rem',
          fontWeight: '500',
          fontFamily: "'Space Grotesk', sans-serif",
          padding: '12px 16px',
          boxShadow: '0 8px 32px rgba(0,0,0,0.5)',
        },
        success: {
          style: {
            borderColor: 'rgba(163,230,53,0.3)',
          },
          iconTheme: { primary: '#a3e635', secondary: '#0a0f1e' },
        },
        error: {
          style: {
            borderColor: 'rgba(244,63,94,0.3)',
          },
          iconTheme: { primary: '#f43f5e', secondary: '#0a0f1e' },
        },
      }}
    />
  </React.StrictMode>,
)
