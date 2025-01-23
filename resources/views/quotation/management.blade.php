@extends('layouts.app')

@section('title', 'Quotation Management')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background-color: #f7f9fc;
    position: relative; /* For absolute positioning of floating divs */
}

.container {
    margin-top: 50px;
    margin-bottom: 100px;
}

table {
    background: #ffffff;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

th,
td {
    text-align: center;
    vertical-align: middle;
}

.section-title {
    margin: 20px 0;
    font-weight: bold;
}

/* Styles for the floating details/edit div */
#details-edit-container {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    background-color: #fff;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 700px;
}

.details-content {
    margin-bottom: 10px;
}

.details-content strong {
    display: inline-block;
    width: 150px;
}

/* Form styles (initially hidden) */
#edit-form {
    display: none;
}

#negotiation-status-container {
        position: absolute;
         bottom: 10px;
          right: 10px;
    }

/* Overlay (optional) */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    #print-content,
    #print-content * {
        visibility: visible;
    }
    #print-content {
        position: absolute;
        left: 0;
        top: 0;
    }
    .content {
        padding: 20px; /* Optional: Adds padding to the content area */
    }

    .no-print {
        display: none;
    }
}

/* Mobile Styles */
@media (max-width: 768px) {
    .container {
        margin-top: 20px; /* Reduce top margin on mobile */
        margin-bottom: 20px; /* Reduce bottom margin on mobile */
    }

    /* Make tables responsive */
    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* For smooth scrolling on iOS */
    }

    /* Modal adjustments for mobile */
    #details-edit-container {
        width: 95%;
        max-width: none; /* Allow modal to take full width */
    }

    /* Adjust form elements for smaller screens */
    #edit-form .form-control {
        font-size: 14px;
    }

    #edit-form .btn {
        font-size: 14px;
        padding: 8px 12px;
    }

    /* --- Button Container Fix --- */
    #buttons-container {
        display: flex;
        flex-wrap: wrap; /* Wrap buttons to the next line if needed */
        justify-content: space-around;
        margin-bottom: 20px; /* Add margin-bottom to create space */
    }

    #buttons-container .btn {
        margin: 5px;
    }

    /* --- Negotiation Status Container Fix --- */
    #negotiation-status-container {
        width: 100%;
        position: inherit;
    }

}
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="container">
        <h2 class="text-center mb-4">Quotation Management</h2>

        <!-- Details/Edit Container -->
        <div id="details-edit-container" class="mb-4">
    <button type="button" class="btn btn-secondary" onclick="hideDetailsEdit()" 
            style="position: absolute; top: 10px; right: 10px;">Close</button>
            <h4>Quotation Details</h4>
            <div id="details-content">
                <!-- Details will be loaded here -->
            </div>
            <!-- Edit Form (Initially Hidden) -->
            <form id="edit-form" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">
                <div class="mb-3">
                    <label for="edit-name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="edit-name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="edit-email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="edit-email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="edit-phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="edit-phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="edit-services" class="form-label">Services</label>
                    <select class="form-control" id="edit-services" name="services[]" multiple required>
                        <!-- Services options will be loaded here -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit-total-cost" class="form-label">Total Cost (RM)</label>
                    <input type="number" class="form-control" id="edit-total-cost" name="totalCost" required readonly>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
            </form>
            <div id="buttons-container">
                <button type="button" id="edit-button" class="btn btn-warning" onclick="showEditForm()">Edit</button>
                <button type="button" id="print-button" class="btn btn-info" onclick="printDetails()">Print</button>
                <!-- Additional Buttons for Negotiation Status -->
                <div id="negotiation-status-container">
                    <button type="button" class="btn btn-success" onclick="updateNegotiationStatus('Success')">Negotiation Success</button>
                    <button type="button" class="btn btn-danger" onclick="updateNegotiationStatus('Failed')">Negotiation Failed</button>
                </div>
                
            </div>
        </div>

        

        <!-- Pending Quotations -->
        <h3 class="section-title">Pending Quotations</h3>
        <div class="table-responsive mb-5">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total Services</th>
                        <th>Total Cost (RM)</th>
                        <th>Assigned Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingQuotations as $id => $quotation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="#" class="show-details" data-quotationId="{{ $id }}">{{ $quotation['name'] }}</a></td>
                            <td>{{ $quotation['email'] }}</td>
                            <td>{{ $quotation['phone'] }}</td>
                            <td>{{ count($quotation['services']) }}</td>
                            <td>{{ number_format($quotation['totalCost'], 2) }}</td>
                            <td>{{ $admins[$quotation['assignedAdmin']]['name'] ?? 'Unassigned' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No pending quotations available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Completed Quotations -->
        <h3 class="section-title">
    Completed Quotations 
    <span class="success-rate" style="font-size: 16px; color: green; float: right; padding-right: 20px; padding-top: 7px; ">
        Success Rate: {{ $successRate }}%
    </span>
</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total Services</th>
                        <th>Total Cost (RM)</th>
                        <th>Assigned Admin</th>
                        <th>Negotiation Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($completedQuotations as $id => $quotation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="#" class="show-details" data-quotationId="{{ $id }}">{{ $quotation['name'] }}</a></td>
                            <td>{{ $quotation['email'] }}</td>
                            <td>{{ $quotation['phone'] }}</td>
                            <td>{{ count($quotation['services']) }}</td>
                            <td>{{ number_format($quotation['totalCost'], 2) }}</td>
                            <td>{{ $admins[$quotation['assignedAdmin']]['name'] ?? 'Unassigned' }}</td>
                            <td style="background-color: {{ isset($quotation['negotiation_status']) ? ($quotation['negotiation_status'] == 'Success' ? 'green' : ($quotation['negotiation_status'] == 'Failed' ? 'red' : 'transparent')) : 'transparent' }}; color: white;">
    {{ $quotation['negotiation_status'] ?? 'Not specified' }}
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No completed quotations available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentQuotationId = null;
        let servicesData = {};

        document.addEventListener('DOMContentLoaded', function() {
            hideDetailsEdit();
            fetchServices();

            const detailLinks = document.querySelectorAll('.show-details');
            detailLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const quotationId = this.dataset.quotationid;
                    currentQuotationId = quotationId;
                    showQuotationDetails(quotationId);
                });
            });
        });

        function fetchServices() {
            fetch('/fetch-services')
                .then(response => response.json())
                .then(data => {
                    servicesData = data;
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                });
        }

        function showQuotationDetails(quotationId) {
            fetch(`/quotation/management/${quotationId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(quotation => {
                    let servicesTable = `
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th style="border: 1px solid #ccc; padding: 8px;">Service Name</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Service Category</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Cost (RM)</th>
            </tr>
        </thead>
        <tbody>
`;

quotation.services.forEach(serviceId => {
    const service = servicesData[serviceId];
    if (service) {
        servicesTable += `
            <tr>
                <td style="border: 1px solid #ccc; padding: 8px;">${service.service || 'Unknown Service'}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${service.category || 'Unknown Category'}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${service.cost || 'No Description'}</td>
            </tr>
        `;
    }
});

servicesTable += `
        </tbody>
    </table>
`;

// Final HTML with table
let detailsHtml = `
    <p><strong>Name:</strong> ${quotation.name}</p>
    <p><strong>Email:</strong> ${quotation.email}</p>
    <p><strong>Phone:</strong> ${quotation.phone}</p>
    <p><strong>Services:</strong></p>
    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        ${servicesTable}
    </div>
    <p><strong>Total Cost:</strong> RM ${quotation.totalCost.toFixed(2)}</p>
    <p><strong>Assigned Admin:</strong> ${quotation.adminName || 'Unassigned'}</p>
`;


                    document.getElementById('details-content').innerHTML = detailsHtml;
                    document.getElementById('edit-form').style.display = 'none';
                    document.getElementById('buttons-container').style.display = 'block';
                    document.querySelector('.overlay').style.display = 'block';
                    document.getElementById('details-edit-container').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching quotation details:', error);
                    alert('Failed to fetch quotation details. Please check the console for errors.');
                });
        }

        function showEditForm() {
    const servicesSelect = document.getElementById('edit-services');
    servicesSelect.innerHTML = ''; // Clear existing options

    // Populate the select with services
    for (const serviceId in servicesData) {
        const option = new Option(servicesData[serviceId].service, serviceId);
        servicesSelect.add(option);
    }

    fetch(`/quotation/management/${currentQuotationId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(quotation => {
            document.getElementById('edit-id').value = currentQuotationId;
            document.getElementById('edit-name').value = quotation.name;
            document.getElementById('edit-email').value = quotation.email;
            document.getElementById('edit-phone').value = quotation.phone;
            document.getElementById('edit-total-cost').value = quotation.totalCost;

            // Populate the select with services and sort them by service name (A to Z)
const sortedServiceIds = Object.keys(servicesData).sort((a, b) => {
    const serviceA = servicesData[a].service.toLowerCase();
    const serviceB = servicesData[b].service.toLowerCase();
    return serviceA.localeCompare(serviceB); // Sort by service name
});

// Populate the select options after sorting
servicesSelect.innerHTML = ''; // Clear existing options
sortedServiceIds.forEach(serviceId => {
    const option = new Option(servicesData[serviceId].service, serviceId);
    servicesSelect.add(option);
});

// Pre-select the services (after sorting)
quotation.services.forEach(serviceId => {
    const option = servicesSelect.querySelector(`option[value="${serviceId}"]`);
    if (option) {
        option.selected = true;
    }
});


            // Auto-update total cost on selection change
            servicesSelect.addEventListener('change', updateTotalCost);

            // Initial total cost calculation based on selected services
            updateTotalCost();

            document.getElementById('details-content').style.display = 'none';
            document.getElementById('edit-form').style.display = 'block';
            document.getElementById('buttons-container').style.display = 'none';
            document.getElementById('edit-button').style.display = 'none';
            document.getElementById('print-button').style.display = 'none';
        })
        .catch(error => {
            console.error('Error fetching quotation details for edit:', error);
            alert('Failed to fetch quotation details for editing. Please check the console for errors.');
        });
}

function updateTotalCost() {
    const servicesSelect = document.getElementById('edit-services');
    let totalCost = 0;

    // Loop through selected options and calculate the total cost
    Array.from(servicesSelect.selectedOptions).forEach(option => {
        const serviceId = option.value;
        const service = servicesData[serviceId];

        if (service && service.cost) {
            totalCost += parseFloat(service.cost);
        }
    });

    // Update the total cost input field
    document.getElementById('edit-total-cost').value = totalCost.toFixed(2);
}


        function hideDetailsEdit() {
            document.getElementById('details-edit-container').style.display = 'none';
            document.querySelector('.overlay').style.display = 'none';
        }

        function cancelEdit() {
            document.getElementById('details-content').style.display = 'block';
            document.getElementById('edit-form').style.display = 'none';
            document.getElementById('buttons-container').style.display = 'block';
            document.getElementById('edit-button').style.display = 'inline-block';
            document.getElementById('print-button').style.display = 'inline-block';
        }

        document.getElementById('edit-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            
            // Append other form fields to the formData
    formData.append('_method', 'PUT');
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('id', document.getElementById('edit-id').value);
    formData.append('name', document.getElementById('edit-name').value);
    formData.append('email', document.getElementById('edit-email').value);
    formData.append('phone', document.getElementById('edit-phone').value);
    formData.append('totalCost', parseFloat(document.getElementById('edit-total-cost').value)); //parse to float before appending

    // Get selected services as an array
    const selectedServices = Array.from(document.getElementById('edit-services').selectedOptions).map(option => option.value);



            fetch(`/quotation/management/${currentQuotationId}`, {
                method: 'POST', // Using POST method
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-HTTP-Method-Override': 'PUT' // For Laravel to treat as PUT
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Quotation updated successfully:', data);
                    alert('Quotation updated successfully!');
                    location.reload()
                })
                .catch(error => {
                    console.error('Error updating quotation:', error);
                    alert('Failed to update quotation. Please check the console for errors.');
                });
        });


        function printDetails() {
    fetch(`/quotation/management/${currentQuotationId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(quotation => {
            // Create a table for the services
            let servicesTable = `
                <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: center;">
                    <thead>
                        <tr style="background-color: #f0f0f0; text-align: center;">
                            <th>Service Name</th>
                            <th>Service Category</th>
                            <th>Service Description</th>
                            <th>Service Cost (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let totalCost = 0;

            quotation.services.forEach(serviceId => {
                const service = servicesData[serviceId];
                if (service) {
                    const serviceCost = parseFloat(service.cost);
                    totalCost += serviceCost;

                    servicesTable += `
                        <tr>
                            <td>${service.service || 'Unknown Service'}</td>
                            <td>${service.category || 'Unknown Category'}</td>
                            <td>${service.description || 'No Description'}</td>
                            <td style="text-align: center;">${serviceCost.toFixed(2)}</td>
                        </tr>
                        </tbody>
                    `;
                }
            });

            // Add the total cost to the table footer
            servicesTable += `
                    
                        <tr style="font-weight: bold;">
                            <td colspan="3" style="text-align: right;">Total</td>
                            <td>${totalCost.toFixed(2)}</td>
                        </tr>
                    
                </table>
            `;

            // Get the current date for the print and format it to "01 July 2022"
const printDate = new Date().toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
});

            // Construct the printable HTML
            let printHtml = `
                <div id="print-content" style="font-family: Arial, sans-serif; margin: 20px;">
                    <!-- Logo -->
                    <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px;">
                        <img src="{{ asset('images/logo/gycc_logo.png') }}" alt="GYCC Logo" style="height: 150px;">
                    </div>


                    <!-- Header -->
                    <h2 style="text-align: center;">Quotation Details</h2>
                    <p style="text-align: center;">Date Printed: ${printDate}</p>
                    <hr>
                    
                    <!-- Quotation Info -->
                    <div style="margin-bottom: 20px;">
                        <p><strong>Name:</strong> ${quotation.name}</p>
                        <p><strong>Email:</strong> ${quotation.email}</p>
                        <p><strong>Phone:</strong> ${quotation.phone}</p>
                        <p><strong>Assigned Admin:</strong> ${quotation.adminName || 'Unassigned'}</p>
                    </div>

                    <!-- Services Table -->
                    ${servicesTable}

                    <!-- Footer -->
                    <hr>
                    <p style="text-align: center; font-size: 12px; color: gray;">
                        This is a computer-generated document. No signature is required.
                    </p>
                </div>
            `;
            

            // Create a temporary iframe for printing
            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.top = '-10000px';
            document.body.appendChild(iframe);

            const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
            iframeDocument.open();
            iframeDocument.write(printHtml);
            iframeDocument.close();

            // Wait for the iframe to load and print
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                document.body.removeChild(iframe); // Remove the iframe after printing
            }, 500); // Delay of .5 seconds to ensure images load
        })
        .catch(error => {
            console.error('Error fetching quotation details for printing:', error);
            alert('Failed to fetch quotation details for printing. Please check the console for errors.');
        });
}

// Function to update the negotiation status
function updateNegotiationStatus(status) {
    const confirmation = confirm(`Are you sure you want to mark the negotiation as ${status}?`);

    if (confirmation) {
        // Send a request to update the negotiation status
        fetch(`/quotation/management/${currentQuotationId}/update-negotiation-status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, // CSRF token for security
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                negotiation_status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status on the UI immediately (if needed)
                alert(`Negotiation marked as ${status}`);
                // Optionally, refresh or update the relevant part of the page
                location.reload();  // Or update the relevant parts dynamically
            } else {
                alert('Failed to update negotiation status.');
            }
        })
        .catch(error => {
            console.error('Error updating negotiation status:', error);
            alert('An error occurred while updating negotiation status.');
        });
    }
}



    </script>
</body>
</html>
@endsection