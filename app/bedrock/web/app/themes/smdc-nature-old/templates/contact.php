<?php
/* Template Name: Contact Page */
get_header(); ?>

<main class="contact-page">
  <section class="contact-hero">
    <h2><span class="highlight">Grow</span> with Us</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
  </section>

  <section class="contact-wrapper">
    <div class="contact-grid">
      
      <!-- Left Column -->
      <div class="contact-info">
        <h3>Head Office</h3>
        <p>123 Main Street, City, Country</p>

        <h3>Email Us</h3>
        <p><a href="mailto:info@example.com">info@example.com</a></p>

        <h3>Call Us</h3>
        <p><a href="tel:+123456789">+1 234 567 89</a></p>

        <h3>Follow Us</h3>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      <!-- Right Column -->
      <div class="contact-form">
        <h2>Say Hello!</h2>
        <form action="#" method="post">
          <select name="inquiry_type">
            <option>The Purpose</option>
            <option>General Inquiry</option>
            <option>Support</option>
          </select>

          <div class="form-row">
            <input type="text" name="first_name" placeholder="First Name">
            <input type="text" name="last_name" placeholder="Last Name">
          </div>

          <div class="form-row">
            <input type="email" name="email" placeholder="Email Address">
            <input type="text" name="phone" placeholder="Phone Number">
          </div>

          <textarea name="message" placeholder="Message"></textarea>

          <button type="submit" class="btn-submit">Submit</button>
        </form>
      </div>

    </div>
  </section>

  <!-- Map -->
  <section class="contact-map">
    <div id="map" style="width:100%; height:450px; border-radius:20px;"></div>
  </section>
</main>

<?php get_footer(); ?>


<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
  function initMap() {
    var location = { lat: 14.5396434, lng: 120.9813301 };
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 17,  // Adjust zoom level
      center: location,
      styles: [ // Custom map colors
        { elementType: "geometry", stylers: [{ color: "#ebe3cd" }] },
        { elementType: "labels.text.fill", stylers: [{ color: "#523735" }] },
        { elementType: "labels.text.stroke", stylers: [{ color: "#f5f1e6" }] },
        {
          featureType: "water",
          elementType: "geometry.fill",
          stylers: [{ color: "#c9c9c9" }]
        }
      ]
    });

    new google.maps.Marker({
      position: location,
      map: map,
      icon: "https://maps.google.com/mapfiles/ms/icons/orange-dot.png" // Custom marker
    });
  }

  window.onload = initMap;
</script>