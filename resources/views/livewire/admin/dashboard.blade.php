<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="text-muted">Welcome, {{ Auth::user()->name }}</h5>
    </div>

    <div class="row">
        <!-- Available Cars -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-2 fw-bold mb-1">{{ $carsCount }}</div>
                            <div class="text-muted fw-medium small">Available Cars</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary"
                            style="width:60px; height:60px;">
                            <i class="bi bi-car-front fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Reservation -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-2 fw-bold mb-1">18</div>
                            <div class="text-muted fw-medium small">Book Reservation</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-25 text-info"
                            style="width:60px; height:60px;">
                            <i class="bi bi-journal-bookmark-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rented Out -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-2 fw-bold mb-1">18</div>
                            <div class="text-muted fw-medium small">Rented Out</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-25 text-info"
                            style="width:60px; height:60px;">
                            <i class="bi bi-key fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Recent Activity -->
    <div class="bg-white rounded-3 shadow-sm p-4">
        <div class="recent-activity">
            <h5 class="mb-4">Recent Reservations</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Reservation ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Vehicle</th>
                            <th scope="col">Period</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#RES-0215</td>
                            <td>John Smith</td>
                            <td>BMW 5 Series</td>
                            <td>24 Oct - 28 Oct</td>
                            <td>$356.00</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>#RES-0214</td>
                            <td>Sarah Johnson</td>
                            <td>Audi Q7</td>
                            <td>23 Oct - 30 Oct</td>
                            <td>$903.00</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>#RES-0213</td>
                            <td>Robert Davis</td>
                            <td>Mercedes E-Class</td>
                            <td>23 Oct - 25 Oct</td>
                            <td>$312.50</td>
                            <td><span class="badge bg-warning">Upcoming</span></td>
                        </tr>
                        <tr>
                            <td>#RES-0212</td>
                            <td>Emily Wilson</td>
                            <td>Toyota Camry</td>
                            <td>22 Oct - 24 Oct</td>
                            <td>$198.75</td>
                            <td><span class="badge bg-secondary">Completed</span></td>
                        </tr>
                        <tr>
                            <td>#RES-0211</td>
                            <td>Michael Brown</td>
                            <td>Ford Mustang</td>
                            <td>21 Oct - 23 Oct</td>
                            <td>$342.00</td>
                            <td><span class="badge bg-secondary">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
