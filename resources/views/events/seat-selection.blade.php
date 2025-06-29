@extends('layouts.app')

@section('title', 'Select Seats - ' . $event->title)

@push('styles')
<style>
    .seat-map-container {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin: 20px 0;
    }
    
    .stage {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        font-weight: bold;
        font-size: 18px;
    }
    
    .section {
        margin-bottom: 30px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .row {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
        gap: 5px;
    }
    
    .row-label {
        font-weight: 600;
        color: #666;
        min-width: 30px;
        text-align: center;
    }
    
    .seat {
        width: 40px;
        height: 40px;
        border: 2px solid #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        color: #333;
    }
    
    .seat:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .seat.available {
        border-color: #28a745;
        background: #d4edda;
        color: #155724;
    }
    
    .seat.selected {
        border-color: #007bff;
        background: #007bff;
        color: white;
        transform: scale(1.1);
    }
    
    .seat.booked {
        border-color: #dc3545;
        background: #f8d7da;
        color: #721c24;
        cursor: not-allowed;
    }
    
    .seat.reserved {
        border-color: #ffc107;
        background: #fff3cd;
        color: #856404;
        cursor: not-allowed;
    }
    
    .seat.aisle {
        background: transparent;
        border: none;
        cursor: default;
    }
    
    .seat.aisle:hover {
        transform: none;
        box-shadow: none;
    }
    
    .legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .legend-seat {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }
    
    .booking-summary {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }
    
    .selected-seats-list {
        max-height: 200px;
        overflow-y: auto;
    }
    
    .selected-seat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    
    .remove-seat {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        cursor: pointer;
    }
    
    .total-price {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
        text-align: center;
        margin: 15px 0;
    }
    
    .section-info {
        background: #e9ecef;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-chair me-2"></i>Select Your Seats
                </h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h5>{{ $event->title }}</h5>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar me-1"></i>{{ $event->date->format('l, F d, Y') }} at {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->venue }}
                    </p>
                </div>

                <div class="seat-map-container">
                    <div class="stage">
                        <i class="fas fa-star me-2"></i>STAGE
                    </div>

                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-seat seat available"></div>
                            <span>Available</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-seat seat selected"></div>
                            <span>Selected</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-seat seat booked"></div>
                            <span>Booked</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-seat seat reserved"></div>
                            <span>Reserved</span>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="filterSeats('all')">All Seats</button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="filterSeats('available')">Available Only</button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="filterSeats('selected')">Selected Only</button>
                        </div>
                    </div>

                    @foreach($seatMap as $sectionName => $sectionRows)
                        <div class="section">
                            <div class="section-title">{{ ucfirst($sectionName) }} Section</div>
                            
                            @php
                                $sectionSeats = collect();
                                foreach($sectionRows as $rowSeats) {
                                    $sectionSeats = $sectionSeats->merge($rowSeats);
                                }
                                $sectionPrice = $sectionSeats->first()->price ?? 0;
                                $availableCount = $sectionSeats->where('status', 'available')->count();
                                $totalCount = $sectionSeats->count();
                            @endphp
                            
                            <div class="section-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Price:</strong> PKR {{ number_format($sectionPrice, 2) }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Available:</strong> {{ $availableCount }}/{{ $totalCount }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Section:</strong> {{ ucfirst($sectionName) }}
                                    </div>
                                </div>
                            </div>
                            
                            @foreach($sectionRows as $rowName => $seats)
                                <div class="row">
                                    <div class="row-label">{{ $rowName }}</div>
                                    
                                    @foreach($seats as $seat)
                                        <div class="seat {{ $seat->status }}" 
                                             data-seat-id="{{ $seat->id }}"
                                             data-seat-number="{{ $seat->seat_number }}"
                                             data-price="{{ $seat->getEffectivePriceAttribute() }}"
                                             onclick="toggleSeat({{ $seat->id }}, '{{ $seat->status }}')">
                                            {{ $seat->seat_number }}
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="booking-summary">
            <h5 class="mb-3">
                <i class="fas fa-shopping-cart me-2"></i>Booking Summary
            </h5>
            
            <div class="selected-seats-list" id="selectedSeatsList">
                <p class="text-muted text-center">No seats selected</p>
            </div>
            
            <div class="total-price" id="totalPrice">
                PKR 0.00
            </div>
            
            <form action="{{ route('bookings.store', $event) }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" name="quantity" id="quantity" value="0">
                
                <!-- Create hidden inputs for each selected seat -->
                <div id="selectedSeatsInputs"></div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="bookButton" disabled>
                        <i class="fas fa-ticket-alt me-2"></i>Book Selected Seats
                    </button>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Event
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedSeats = [];
let seatPrices = {};

// Initialize seat prices
document.querySelectorAll('.seat').forEach(seat => {
    const seatId = seat.dataset.seatId;
    const price = parseFloat(seat.dataset.price);
    seatPrices[seatId] = price;
});

function toggleSeat(seatId, status) {
    if (status === 'booked' || status === 'reserved') {
        return; // Can't select booked or reserved seats
    }
    
    const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
    const seatNumber = seatElement.dataset.seatNumber;
    
    if (selectedSeats.includes(seatId)) {
        // Remove seat from selection
        selectedSeats = selectedSeats.filter(id => id !== seatId);
        seatElement.classList.remove('selected');
        seatElement.classList.add('available');
    } else {
        // Add seat to selection
        selectedSeats.push(seatId);
        seatElement.classList.remove('available');
        seatElement.classList.add('selected');
    }
    
    updateBookingSummary();
}

function updateBookingSummary() {
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const totalPriceElement = document.getElementById('totalPrice');
    const quantityInput = document.getElementById('quantity');
    const selectedSeatsInputs = document.getElementById('selectedSeatsInputs');
    const bookButton = document.getElementById('bookButton');
    
    if (selectedSeats.length === 0) {
        selectedSeatsList.innerHTML = '<p class="text-muted text-center">No seats selected</p>';
        totalPriceElement.textContent = 'PKR 0.00';
        bookButton.disabled = true;
        selectedSeatsInputs.innerHTML = '';
        quantityInput.value = 0;
    } else {
        let totalPrice = 0;
        let seatsHtml = '';
        let inputsHtml = '';
        
        selectedSeats.forEach(seatId => {
            const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
            const seatNumber = seatElement.dataset.seatNumber;
            const price = seatPrices[seatId];
            totalPrice += price;
            
            seatsHtml += `
                <div class="selected-seat-item">
                    <span>Seat ${seatNumber}</span>
                    <div>
                        <span class="me-2">PKR ${price.toFixed(2)}</span>
                        <button type="button" class="remove-seat" onclick="toggleSeat(${seatId}, 'available')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Create hidden input for each selected seat
            inputsHtml += `<input type="hidden" name="selected_seats[]" value="${seatId}">`;
        });
        
        selectedSeatsList.innerHTML = seatsHtml;
        totalPriceElement.textContent = `PKR ${totalPrice.toFixed(2)}`;
        bookButton.disabled = false;
        selectedSeatsInputs.innerHTML = inputsHtml;
        
        // Update quantity to match selected seats count
        quantityInput.value = selectedSeats.length;
        
        // Debug logging
        console.log('Selected seats:', selectedSeats);
        console.log('Quantity set to:', selectedSeats.length);
        console.log('Form inputs created:', inputsHtml);
    }
}

// Add form submission debugging
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const quantity = document.getElementById('quantity').value;
    const selectedSeatsInputs = document.querySelectorAll('input[name="selected_seats[]"]');
    
    console.log('Form submission - Quantity:', quantity);
    console.log('Form submission - Selected seats count:', selectedSeatsInputs.length);
    console.log('Form submission - Selected seats values:', Array.from(selectedSeatsInputs).map(input => input.value));
    
    if (parseInt(quantity) !== selectedSeatsInputs.length) {
        e.preventDefault();
        alert('Error: Quantity and selected seats count do not match. Please try selecting seats again.');
        return false;
    }
});

function filterSeats(filter) {
    const seats = document.querySelectorAll('.seat');
    
    seats.forEach(seat => {
        const row = seat.closest('.row');
        const section = row.closest('.section');
        
        if (filter === 'all') {
            row.style.display = 'flex';
            section.style.display = 'block';
        } else if (filter === 'available') {
            if (seat.classList.contains('available')) {
                row.style.display = 'flex';
                section.style.display = 'block';
            } else {
                row.style.display = 'none';
            }
        } else if (filter === 'selected') {
            if (seat.classList.contains('selected')) {
                row.style.display = 'flex';
                section.style.display = 'block';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

// Auto-refresh seat availability every 30 seconds
setInterval(function() {
    fetch(`{{ route('events.seats.available', $event) }}`)
        .then(response => response.json())
        .then(data => {
            data.seats.forEach(seat => {
                const seatElement = document.querySelector(`[data-seat-id="${seat.id}"]`);
                if (seatElement) {
                    seatElement.className = `seat ${seat.status}`;
                    if (seat.status === 'available') {
                        seatElement.onclick = () => toggleSeat(seat.id, seat.status);
                    } else {
                        seatElement.onclick = null;
                    }
                }
            });
        })
        .catch(error => console.error('Error refreshing seats:', error));
}, 30000);
</script>
@endpush 