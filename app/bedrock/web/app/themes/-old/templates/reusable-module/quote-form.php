<?php
// form.php
?>
    <form id="quote-form" method="post">
        <?php wp_nonce_field('submit_quote_form', 'quote_form_nonce'); ?>
        <input type="hidden" name="quote_form_title" value="<?php echo esc_attr( get_the_title( get_queried_object_id() ) ); ?>">

          <!-- Property of Interest -->
        <div class="form-group select-wrapper">
            <label for="property_of_interest">Property of Interest</label>
            <select id="property_of_interest" name="property_of_interest" required>
                <option value="">Select a property</option>
                <option value="Property 1">Property 1</option>
                <option value="Property 2">Property 2</option>
                <option value="Property 3">Property 3</option>
            </select>
            <span class="select-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path></svg>
            </span>
        </div>

      <!-- Name Fields -->
      <div class="form-row">
        <div class="form-group half">
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name" autocomplete="first-name" required>
        </div>
        <div class="form-group half">
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" required>
        </div>
      </div>

      <!-- Contact -->
      <div class="form-row">
        <div class="form-group half">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" autocomplete="email" required>
        </div>
        <div class="form-group half">
          <label for="number">Contact Number</label>
          <input type="text" id="number" name="number" autocomplete="tel" required>
        </div>
      </div>

      <!-- Country -->
      <div class="form-group select-wrapper">
        <label for="country_of_residence">Country of Residence</label>
        <select id="country_of_residence" name="country_of_residence"  required>
          <option value="">-- Select your country --</option>
          <?php echo get_country_list_options(); ?>
        </select>
        <span class="select-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path></svg>
        </span>
      </div>

      <!-- Consent -->
      <div class="form-row consent">
        <input type="checkbox" id="consent" name="consent" value="1">
        <p for="consent">I agree to the collection of my data for quotation purposes.</p>
      </div>

      <!-- Submit -->
      <button type="submit" name="submit_quote" class="submit-btn">Submit</button>
    </form>

<!-- Modal placeholders -->
<div id="quote-modal" class="modal">
  <div class="modal-content">
      <p id="quote-message"></p>
      <button id="close-quote-modal" >Close</button>
  </div>
</div>
