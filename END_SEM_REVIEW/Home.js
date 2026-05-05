import React from "react";
import { useNavigate } from "react-router-dom";
import "../App.css";

function Home({ event, available }) {
  const navigate = useNavigate();

  return (
    <div className="page">
      <div className="glass-card">
        <h2>🎉 {event.name}</h2>
        <p>🏫 {event.department}</p>
        <p>📅 {event.date}</p>
        <p>📍 {event.venue}</p>
        <p>💰 ₹{event.price}</p>
        <p>🎟 Available: {available}</p>

        <button onClick={() => navigate("/booking")}>
          Book Now 🚀
        </button>
      </div>
    </div>
  );
}

export default Home;