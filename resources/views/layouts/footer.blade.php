 <!-- Newsletter Section -->
 <section class="newsletter-section">
     <div class="container-fluid position-relative">
         <div class="row align-items-center">
             <div class="col-lg-6 mb-4 mb-lg-0">
                 <h2 class="fw-bold mb-3">Stay Updated</h2>
                 <p class="mb-0">Subscribe to our newsletter and get 10% off your first order</p>
             </div>
             <div class="col-lg-6">
                 <form id="newsletterForm" class="d-flex">
                     <input type="email" class="form-control newsletter-input me-2" placeholder="Enter your email"
                         required>
                     <button class="btn btn-light px-4" type="submit">
                         Subscribe <i class="fas fa-paper-plane ms-2"></i>
                     </button>
                 </form>
             </div>
         </div>
     </div>
 </section>
 <footer class="bg-dark text-light pt-5 pb-3 mt-5">
     <div class="container">
         <div class="row">
             <!-- About Section -->
             <div class="col-md-4 mb-4 mb-md-0">
                 <h5 class="fw-bold mb-3">About Us</h5>
                 <p class="text-secondary">Ecommerce BD is your trusted online shop for quality products, fast delivery,
                     and excellent customer service. Shop with confidence and enjoy exclusive deals every day!</p>
             </div>
             <!-- Quick Links -->
             <div class="col-md-4 mb-4 mb-md-0">
                 <h5 class="fw-bold mb-3">Quick Links</h5>
                 <ul class="list-unstyled">
                     <li><a href="/" class="text-secondary text-decoration-none">Home</a></li>
                     <li><a href="/shop" class="text-secondary text-decoration-none">Shop</a></li>
                     <li><a href="/about" class="text-secondary text-decoration-none">About</a></li>
                     <li><a href="/contact" class="text-secondary text-decoration-none">Contact</a></li>
                 </ul>
             </div>
             <!-- Contact & Social -->
             <div class="col-md-4">
                 <h5 class="fw-bold mb-3">Contact</h5>
                 <p class="text-secondary mb-1">Email: support@ecommercebd.com</p>
                 <p class="text-secondary mb-2">Phone: +880 1234 567890</p>
                 <div class="d-flex gap-3 mt-2">
                     <a href="#" class="text-secondary fs-5" aria-label="Facebook"><i
                             class="bi bi-facebook"></i></a>
                     <a href="#" class="text-secondary fs-5" aria-label="Twitter"><i
                             class="bi bi-twitter"></i></a>
                     <a href="#" class="text-secondary fs-5" aria-label="Instagram"><i
                             class="bi bi-instagram"></i></a>
                 </div>
             </div>
         </div>
         <hr class="border-secondary mt-4 mb-3">
         <div class="text-center text-secondary small">
             &copy; {{ date('Y') }} Ecommerce BD. All rights reserved.
         </div>
     </div>
 </footer>
