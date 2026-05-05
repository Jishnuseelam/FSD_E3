import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import emailjs from "@emailjs/browser";
import "../App.css";

function Booking({ event, available, setAvailable, setSummary }) {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    name: "",
    email: "",
    dept: "",
    tickets: ""
  });

  const [error, setError] = useState("");

  const validate = () => {
    if (!form.name || !form.email || !form.dept || !form.tickets) {
      return "⚠ All fields are required";
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if (!emailPattern.test(form.email)) {
      return "❌ Please enter valid email";
    }

    if (form.tickets <= 0) {
      return "❌ Tickets must be greater than 0";
    }

    if (form.tickets > available) {
      return "❌ Not enough tickets available";
    }

    return "";
  };

  const submit = () => {
    const err = validate();

    if (err) {
      setError(err);
      return;
    }

    const total = form.tickets * event.price;

    // Save booking history
    const existing = JSON.parse(localStorage.getItem("history")) || [];

    existing.push({
      name: form.name,
      event: event.name,
      tickets: form.tickets,
      total: total,
      date: new Date().toLocaleString()
    });

    localStorage.setItem("history", JSON.stringify(existing));

    // Send confirmation email
    emailjs.send(
      "service_ssn64gz",
      "template_xzc7qs7",
      {
        user_name: form.name,
        user_email: form.email,
        event_name: event.name,
        tickets: form.tickets,
        total_amount: total
      },
      "qia8V-YRC43C3AC2U"
    )
    .then(() => {
      console.log("Email Sent Successfully");
    })
    .catch((error) => {
      console.log(error);
    });

    // Update available tickets
    setAvailable(available - form.tickets);

    // Summary data
    setSummary({
      name: form.name,
      event: event.name,
      tickets: form.tickets,
      total: total
    });

    setError("");

    navigate("/summary");
  };

  const resetForm = () => {
    setForm({
      name: "",
      email: "",
      dept: "",
      tickets: ""
    });

    setError("");
  };

  return (
    <div className="page">
      <div className="glass-card">
        <h2>🎟 Book Tickets</h2>

        {error && <p style={{ color: "yellow" }}>{error}</p>}

        <input
          placeholder="Name"
          value={form.name}
          onChange={(e) =>
            setForm({ ...form, name: e.target.value })
          }
        />

        <input
          placeholder="Email"
          value={form.email}
          onChange={(e) =>
            setForm({ ...form, email: e.target.value })
          }
        />

        <input
          placeholder="Department"
          value={form.dept}
          onChange={(e) =>
            setForm({ ...form, dept: e.target.value })
          }
        />

        <input
          type="number"
          placeholder="Tickets"
          value={form.tickets}
          onChange={(e) =>
            setForm({
              ...form,
              tickets: Number(e.target.value)
            })
          }
        />

        <button onClick={submit}>
          Confirm Booking ✅
        </button>

        <button onClick={resetForm}>
          Reset 🔄
        </button>
      </div>
    </div>
  );
}

export default Booking;