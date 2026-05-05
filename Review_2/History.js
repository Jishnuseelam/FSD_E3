import React, { useEffect, useState } from "react";
import "../App.css";

function History() {
  const [data, setData] = useState([]);

  useEffect(() => {
    const stored = JSON.parse(localStorage.getItem("history")) || [];
    setData(stored);
  }, []);

  // 🔴 Delete specific item
  const deleteItem = (index) => {
    const updated = [...data];
    updated.splice(index, 1);

    setData(updated);
    localStorage.setItem("history", JSON.stringify(updated));
  };

  return (
    <div className="page">
      <div className="glass-card">
        <h2>📜 Booking History</h2>

        {data.length === 0 ? (
          <p>No bookings yet</p>
        ) : (
          data.map((item, index) => (
            <div key={index} style={{ marginBottom: "10px" }}>
              <p>👤 {item.name}</p>
              <p>🎪 {item.event}</p>
              <p>🎟 {item.tickets}</p>
              <p>💰 ₹{item.total}</p>
              <p>🕒 {item.date}</p>

              <button onClick={() => deleteItem(index)}>
                ❌ Delete
              </button>

              <hr />
            </div>
          ))
        )}
      </div>
    </div>
  );
}

export default History;