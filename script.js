// Property Data
const properties = {
    1: {
        title: "Modern House • Lahore",
        price: "PKR 6.5 Cr",
        image: "assets/home-1.jpeg",
        description: `
            <p><strong>Property Details:</strong></p>
            <p>This stunning modern house in Lahore offers luxurious living with contemporary design. Features include:</p>
            <ul>
                <li>5 spacious bedrooms with attached bathrooms</li>
                <li>4 modern bathrooms with premium fixtures</li>
                <li>Large open-plan living and dining area</li>
                <li>Modern kitchen with latest appliances</li>
                <li>Private garden and outdoor space</li>
                <li>Covered parking for 2 vehicles</li>
                <li>Located in premium neighborhood</li>
            </ul>
        `
    },
    2: {
        title: "Villa • Islamabad",
        price: "PKR 3.5 Cr",
        image: "assets/home-2.jpeg",
        description: `
            <p><strong>Property Details:</strong></p>
            <p>Beautiful corner villa in prime Islamabad location, perfect for families:</p>
            <ul>
                <li>1 Kanal plot size</li>
                <li>Corner location with extra privacy</li>
                <li>Modern architectural design</li>
                <li>Multiple living areas</li>
                <li>Servant quarters</li>
                <li>Beautiful landscaping</li>
                <li>Close to main boulevard</li>
            </ul>
        `
    },
    3: {
        title: "Designer Villa",
        price: "PKR 10.00 Cr",
        image: "assets/home-3.jpg",
        description: `
            <p><strong>Property Details:</strong></p>
            <p>Exclusive designer villa with premium finishes and luxury amenities:</p>
            <ul>
                <li>2 Kanal prime location</li>
                <li>Award-winning architectural design</li>
                <li>Swimming pool and gym</li>
                <li>Home theater and entertainment area</li>
                <li>Smart home automation</li>
                <li>Private security and gated community</li>
                <li>Marble and imported finishes throughout</li>
            </ul>
        `
    },
    4: {
        title: "Traditional Home",
        price: "PKR 7.5 Cr",
        image: "assets/home-4.png",
        description: `
            <p><strong>Property Details:</strong></p>
            <p>Elegant traditional villa blending cultural architecture with modern comfort:</p>
            <ul>
                <li>Classic Pakistani architectural style</li>
                <li>Large courtyard and traditional sitting areas</li>
                <li>High ceilings with decorative work</li>
                <li>Modern kitchen and bathrooms</li>
                <li>Family and guest sections</li>
                <li>Beautiful woodwork and craftsmanship</li>
                <li>Perfect for extended families</li>
            </ul>
        `
    },
    5: {
        title: "Family Home • Lahore",
        price: "PKR 5.5 Cr",
        image: "assets/home-6.jpg",
        description: `
            <p><strong>Property Details:</strong></p>
            <p>Stylish 10 marla family home in sought-after Lahore location:</p>
            <ul>
                <li>10 Marla well-designed layout</li>
                <li>Contemporary styling throughout</li>
                <li>Perfect for growing families</li>
                <li>Modern amenities and fittings</li>
                <li>Close to schools and shopping</li>
                <li>Secure neighborhood</li>
                <li>Ready to move in</li>
            </ul>
        `
    },
    6: {
        title: "Hill Home • Modern",
        price: "PKR 11.75 Cr",
        image: "assets/home-5.jpg",
        description: `
            <p><strong>Property Details:</strong></p>
            <p>Breathtaking modern home with scenic hill views and ultimate privacy:</p>
            <ul>
                <li>Spectacular mountain and valley views</li>
                <li>Contemporary architectural masterpiece</li>
                <li>Floor-to-ceiling windows</li>
                <li>Multiple terraces and balconies</li>
                <li>Private and secluded location</li>
                <li>Premium imported materials</li>
                <li>Perfect for nature lovers</li>
            </ul>
        `
    }
};

// Location coordinates for Google Maps
const locations = {
    "DHA Lahore Phase 6": "31.4697,74.4084",
    "Behria Town Lahore": "31.3449,74.1926",
    "Park View City Islamabad": "33.5729,73.1604",
    "Sector F-6 Islamabad": "33.7261,73.0685",
    "DHA Rawalpindi": "33.5223,73.1364"
};

// Mobile Navigation Toggle
const toggle = document.querySelector('.toggle-btn');
const navLinks = document.querySelector('.nav-links');
const form = document.getElementById('contactForm');
const modal = document.getElementById('modal');
const modalClose = document.getElementById('modal-close');
const modalTitle = document.getElementById('modal-title');
const modalText = document.getElementById('modal-text');
const propertyModal = document.getElementById('propertyModal');
const buyerForm = document.getElementById('buyerForm');

// Toggle mobile menu
toggle.addEventListener('click', () => {
    navLinks.classList.toggle('show');
});

// Smooth scroll for navigation links
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', (e) => {
        const href = link.getAttribute('href');
        if (href.startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
                navLinks.classList.remove('show');
            }
        }
    });
});

// Contact Form submission handler WITH BACKEND
form.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    
    fetch('contact_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            modalTitle.textContent = 'Thank You!';
            modalText.textContent = data.message;
            form.reset();
        } else {
            modalTitle.textContent = 'Error';
            modalText.textContent = data.message;
        }
        modal.style.display = 'flex';
    })
    .catch(error => {
        modalTitle.textContent = 'Error';
        modalText.textContent = 'Failed to submit form. Please try again.';
        modal.style.display = 'flex';
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

// Close contact modal
modalClose.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Close modal when clicking outside
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Open Property Modal
function openPropertyModal(propertyId) {
    const property = properties[propertyId];
    if (!property) return;

    document.getElementById('propertyImage').src = property.image;
    document.getElementById('propertyTitle').textContent = property.title;
    document.getElementById('propertyPrice').textContent = property.price;
    document.getElementById('propertyDescription').innerHTML = property.description;

    // Store property info in form for reference
    buyerForm.dataset.propertyId = propertyId;
    buyerForm.dataset.propertyName = property.title;
    
    propertyModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close Property Modal
function closePropertyModal() {
    propertyModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    buyerForm.reset();
}

// Close property modal when clicking outside
propertyModal.addEventListener('click', (e) => {
    if (e.target === propertyModal) {
        closePropertyModal();
    }
});

// Buyer Form Submission WITH BACKEND
buyerForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const propertyId = buyerForm.dataset.propertyId;
    const propertyName = buyerForm.dataset.propertyName;
    const buyerName = document.getElementById('buyerName').value.trim();
    const buyerEmail = document.getElementById('buyerEmail').value.trim();
    const buyerPhone = document.getElementById('buyerPhone').value.trim();
    
    if (!buyerName || !buyerEmail || !buyerPhone) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Create FormData object
    const formData = new FormData();
    formData.append('property_id', propertyId);
    formData.append('property_name', propertyName);
    formData.append('buyer_name', buyerName);
    formData.append('buyer_email', buyerEmail);
    formData.append('buyer_phone', buyerPhone);
    formData.append('buyer_address', document.getElementById('buyerAddress').value.trim());
    formData.append('buyer_message', document.getElementById('buyerMessage').value.trim());
    
    const submitBtn = buyerForm.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    
    fetch('buyer_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Close property modal
        closePropertyModal();
        
        if (data.success) {
            modalTitle.textContent = 'Interest Submitted Successfully!';
            modalText.textContent = data.message;
        } else {
            modalTitle.textContent = 'Error';
            modalText.textContent = data.message;
        }
        modal.style.display = 'flex';
    })
    .catch(error => {
        closePropertyModal();
        modalTitle.textContent = 'Error';
        modalText.textContent = 'Failed to submit. Please try again.';
        modal.style.display = 'flex';
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

// Open Location Map
function openLocationMap(locationName) {
    const mapContainer = document.getElementById('map-container');
    const mapTitle = document.getElementById('map-title');
    const locationMap = document.getElementById('location-map');
    
    const coordinates = locations[locationName];
    
    if (coordinates) {
        locationMap.src = `https://maps.google.com/maps?q=${coordinates}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
        
        mapTitle.textContent = `${locationName} - Map View`;
        mapContainer.classList.add('active');
        
        // Smooth scroll to map
        setTimeout(() => {
            mapContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }
}

// Close map when clicking on a different location
document.querySelectorAll('.location-bar').forEach(bar => {
    bar.addEventListener('click', function() {
        document.querySelectorAll('.location-bar').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});