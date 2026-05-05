import React from "react";
import { useNavigate } from "react-router-dom";
import "../App.css";

function Summary({ summary }) {
  const navigate = useNavigate();

  if (!summary) return <h2>No Booking Found</h2>;

  return (
    <div className="page">
      <div className="glass-card">
        <h2>🎉 Booking Successful</h2>

        <p>👤 <b>User:</b> {summary.name}</p>
        <p>🎪 <b>Event:</b> {summary.event}</p>
        <p>🎟 <b>Tickets:</b> {summary.tickets}</p>
        <p>💰 <b>Total Amount:</b> ₹{summary.total}</p>

        <button onClick={() => navigate("/")}>
          🏠 Back Home
        </button>
      </div>
    </div>
  );
}

export default Summary;