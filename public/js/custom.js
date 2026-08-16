/* Custom JavaScript for Doctor Appointment System */

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Add loading spinner for form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            // Create spinner overlay
            const spinnerOverlay = document.createElement('div');
            spinnerOverlay.className = 'spinner-overlay';
            spinnerOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
            document.body.appendChild(spinnerOverlay);
        });
    });
    
    // Availability slot selection
    const availabilitySlots = document.querySelectorAll('.availability-slot');
    availabilitySlots.forEach(slot => {
        slot.addEventListener('click', function() {
            // Remove selected class from all slots
            availabilitySlots.forEach(s => s.classList.remove('selected'));
            // Add selected class to clicked slot
            this.classList.add('selected');
            // Update hidden input with selected slot
            const slotInput = document.getElementById('selected_slot');
            if (slotInput) {
                slotInput.value = this.dataset.slotId;
            }
        });
    });
    
    // Date range picker initialization for reports
    const dateRangePicker = document.getElementById('date-range-picker');
    if (dateRangePicker) {
        new DateRangePicker(dateRangePicker, {
            format: 'yyyy-mm-dd',
            autohide: true
        });
    }
    
    // Doctor search functionality
    const searchInput = document.getElementById('doctor-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const doctorCards = document.querySelectorAll('.doctor-card');
            
            doctorCards.forEach(card => {
                const doctorName = card.querySelector('.doctor-name').textContent.toLowerCase();
                const doctorSpecialty = card.querySelector('.doctor-specialty').textContent.toLowerCase();
                
                if (doctorName.includes(searchTerm) || doctorSpecialty.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Specialty filter
    const specialtyFilter = document.getElementById('specialty-filter');
    if (specialtyFilter) {
        specialtyFilter.addEventListener('change', function() {
            const selectedSpecialty = this.value.toLowerCase();
            const doctorCards = document.querySelectorAll('.doctor-card');
            
            doctorCards.forEach(card => {
                const doctorSpecialty = card.querySelector('.doctor-specialty').textContent.toLowerCase();
                
                if (selectedSpecialty === 'all' || doctorSpecialty === selectedSpecialty) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Time slot validation
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    if (startTimeInput && endTimeInput) {
        endTimeInput.addEventListener('change', function() {
            const startTime = startTimeInput.value;
            const endTime = this.value;
            
            if (startTime && endTime && startTime >= endTime) {
                alert('End time must be after start time');
                this.value = '';
            }
        });
    }
    
    // Appointment cancellation confirmation
    const cancelButtons = document.querySelectorAll('.cancel-appointment');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to cancel this appointment?')) {
                e.preventDefault();
            }
        });
    });
    
    // Profile image preview
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profile_image_preview');
    
    if (profileImageInput && profileImagePreview) {
        profileImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
