@extends('layouts.myapp')
@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var flatpickrElement = document.getElementById('myflatpickr');
        if (flatpickrElement) {
            var disabledDates = [
                @foreach($car_reservation as $reservation)
                    {
                        from: "{{ $reservation->start_date }}",
                        to: "{{ $reservation->end_date }}"
                    },
                @endforeach
            ];
            // console.log(disabledDates);

            flatpickrElement.flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                disable: disabledDates,
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        var startDate = selectedDates[0];
                        var endDate = selectedDates[1];
                        if (startDate && endDate && startDate < endDate) {
                            var duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                            var pricePerDay = {{ $car->price_per_day }};
                            var totalPrice = duration * pricePerDay;
                            document.getElementById('duration').innerText = `Estimated Duration: ${duration} days`;
                            document.getElementById('total-price').innerText = `Estimated Price: ${totalPrice} $`;
                        } else if (startDate && endDate && toString(startDate) == toString(endDate)) {
                            var pricePerDay = {{ $car->price_per_day }};
                            document.getElementById('duration').innerText = `Estimated Duration: 1 day`;
                            document.getElementById('total-price').innerText = `Estimated Price: ${pricePerDay} $`;
                        } else {
                            document.getElementById('duration').innerText = 'Estimated Duration: -- days';
                            document.getElementById('total-price').innerText = 'Estimated Price: -- $';
                        }
                    } else {
                        document.getElementById('duration').innerText = 'Estimated Duration: -- days';
                        document.getElementById('total-price').innerText = 'Estimated Price: -- $';
                    }
                }
            });
        }
    });
</script>
    <div class="mx-auto max-w-screen-xl bg-white rounded-md p-6 m-8 ">
        <div class="flex justify-between md:flex-row flex-col ">
            {{-- -------------------------------------------- left -------------------------------------------- --}}
            <div class="md:w-2/3  md:border-r border-gray-800 p-2">

                <h2 class=" ms-4 max-w-full font-car md:text-6xl text-4xl">{{ $car->brand }} {{ $car->model }}
                    {{ $car->engine }}
                </h2>

                <div class="flex items-end mt-8 ms-4">
                    <h3 class="font-car text-gray-500 text-2xl">Price:</h3>
                    <p>
                        <span
                            class="text-3xl font-bold text-pr-400 ms-3 me-1 border border-pr-400 p-2 rounded-md">{{ $car->price_per_day }}
                            $</span>
                        @if ($car->reduce > 0)
                            <span
                                class="text-lg font-medium text-red-500 line-through">{{ intval(($car->price_per_day * 100) / (100 - $car->reduce)) }}
                                $
                            </span>
                        @endif
                    </p>
                </div>

                <div class=" flex items-center justify-around mt-10 me-10">
                    <div class="w-1/5 md:w-1/3 h-[0.25px] bg-gray-500 "> </div>
                    <p>Order Informations</p>
                    <div class="w-1/5 md:w-1/3 h-[0.25px] bg-gray-500 "> </div>

                </div>

                <div class="px-6 md:me-8">
                    <form id="reservation_form" action="{{ route('car.reservationStore', ['car' => $car->id]) }}"
                        method="POST">
                        @csrf
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                            {{-- <input type="text" hidden name="user" value="{{ Auth::user()->id }}"> --}}

                            <div class="sm:col-span-3">
                                <label for="full-name" class="block text-sm font-medium leading-6 text-gray-900">Full
                                    Name</label>
                                <div class="mt-2">
                                    <input type="text" name="full-name" id="full-name" value="{{ $user->name }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6">
                                </div>
                                @error('name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="email"
                                    class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <div class="mt-2">
                                    <input type="text" name="email" id="email" value="{{ $user->email }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6">
                                </div>
                                @error('email')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- New Date Input --}}

                            <div class="sm:col-span-full">
                                <label for="reservation" class="block text-sm font-medium leading-6 text-gray-900">Reservation Dates</label>
                                <input type="text" id="myflatpickr" name="reservation_dates" class="w-full rounded-md" placeholder="Reservation Dates" />
                            </div>

                        </div>
                        <div class="mt-12 md:block hidden  ">
                            <button type="submit"
                                class="text-white bg-pr-400 p-3 w-full rounded-lg font-bold hover:bg-black shadow-xl hover:shadow-none ">Order
                                Now</button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- -------------------------------------------- right -------------------------------------------- --}}
            <div class="md:w-1/3 flex flex-col justify-start items-center">
                <div class="relative mx-3 mt-3 flex h-[200px] w-3/4   overflow-hidden rounded-xl shadow-lg">
                    <img loading="lazy" class="h-full w-full object-cover" src="{{ $car->image }}" alt="product image" />
                    <span
                        class="absolute w-24 h-8 py-1 top-0 left-0 m-2 rounded-full bg-pr-400 px-2 text-center text-sm font-medium text-white">{{ $car->reduce }}
                        %
                        OFF</span>
                </div>
                <p class=" ms-4 max-w-full font-car text-xl mt-3 md:block hidden">{{ $car->brand }} {{ $car->model }}
                    {{ $car->engine }}
                </p>
                <div class="mt-3 ms-4 md:block hidden">
                    <div class="flex items-center">
                        @if ($reviews->count() > 0)
                            @php
                                $avgRating = round($reviews->avg('rating'), 1);
                                $roundedStars = $avgRating - floor($avgRating) >= 0.5 ? ceil($avgRating) : floor($avgRating);
                            @endphp
                            @for ($i = 0; $i < $roundedStars; $i++)
                                <svg aria-hidden="true" class="h-4 w-4 text-pr-300" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                            <span class="mr-2 ml-3 rounded bg-pr-300 px-2.5 py-0.5 text-sm font-semibold">{{ $avgRating }}</span>
                        @else
                            @for ($i = 0; $i < $car->stars; $i++)
                                <svg aria-hidden="true" class="h-4 w-4 text-pr-300" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                            <span class="mr-2 ml-3 rounded bg-pr-300 px-2.5 py-0.5 text-sm font-semibold">{{ $car->stars }}.0</span>
                        @endif
                    </div>
                </div>


                <div class=" w-full   mt-8 ms-8">
                    <p id="duration" class="font-car text-gray-600 text-lg ms-2">Estimated Duration: -- days</p>
                </div>

                <div class=" w-full   mt-8 ms-8">
                    <p id="total-price" class="font-car text-gray-600 text-lg ms-2">Estimated Price: -- $</p>
                </div>
                <div id="mobile_submit_button" class="mt-12 w-full md:hidden  ">
                    <button type="submit"
                        class="text-white bg-pr-400 p-3 w-full rounded-lg font-bold hover:bg-black shadow-xl hover:shadow-none ">Order
                        Now</button>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <h3 class="text-2xl font-bold mb-4">Reviews</h3>
            <div class="space-y-4">
                @foreach ($reviews->sortByDesc('created_at') as $review)
                    <div class="border rounded-lg p-4 shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-bold">{{ $review->user->name }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $review->created_at->addHours(3)->format('F j, Y, g:i a') }}
                                </p>
                            </div>
                            <div class="flex items-center">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <svg class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="mt-2">{{ $review->comment }}</p>
                        @if ($review->user_id === auth()->id())
                            <div class="flex justify-end space-x-2 mt-4">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded" onclick="openEditModal({{ $review->id }}, '{{ $review->rating }}', '{{ $review->comment }}')">Edit</button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded" onclick="deleteReview({{ $review->id }})">Delete</button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8">
            @if ($userReview)
                {{-- <div class="border rounded-lg p-4 shadow-md bg-gray-100">
                    <h3 class="text-xl font-bold text-gray-700">You've already made a review for this car.</h3>
                </div> --}}
            @elseif ($canReview)
                <h3 class="text-xl font-bold mb-4">Add a Review</h3>
                <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium">Rating</label>
                        <input type="number" name="rating" id="rating" min="1" max="5" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium">Comment</label>
                        <textarea name="comment" id="comment" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
                    </div>
                    <button type="button" onclick="submitReview()" class="bg-green-500 text-white px-4 py-2 rounded">Submit Review</button>
                </form>
            @else
                <h3 class="text-xl font-bold mb-4">Add a Review</h3>
                <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="car_id" value="{{ $car->id }}">
                    <div class="mb-4">
                        <label for="rating" class="block text-sm font-medium">Rating</label>
                        <input type="number" name="rating" id="rating" min="1" max="5" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium">Comment</label>
                        <textarea name="comment" id="comment" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
                    </div>
                    <button onclick="showReservationRequiredAlert()" class="bg-green-500 text-white px-4 py-2 rounded">Submit Review</button>
                </form>
            @endif
        </div>

        <!-- Edit Review Modal -->
        <div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-xl font-bold mb-4">Edit Review</h3>
                <form id="editReviewForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editReviewId" name="review_id">
                    <div class="mb-4">
                        <label for="editRating" class="block text-sm font-medium">Rating</label>
                        <input type="number" id="editRating" name="rating" min="1" max="5" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="editComment" class="block text-sm font-medium">Comment</label>
                        <textarea id="editComment" name="comment" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-500 text-white px-3 py-1 rounded" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function showReservationRequiredAlert() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Reservation Required',
                    text: 'You must have a previous or current reservation for this car to leave a review.',
                });
            }

            function submitReview() {
                Swal.fire({
                    title: 'Submitting Review',
                    text: 'Your review is being submitted...',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000,
                }).then(() => {
                    document.getElementById('reviewForm').submit();
                });
            }

            function openEditModal(reviewId, rating, comment) {
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editReviewId').value = reviewId;
                document.getElementById('editRating').value = rating;
                document.getElementById('editComment').value = comment;
                document.getElementById('editReviewForm').action = `/reviews/${reviewId}`;
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function deleteReview(reviewId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`deleteReviewForm-${reviewId}`).submit();
                    }
                });
            }
        </script>
        @foreach ($reviews as $review)
            <form id="deleteReviewForm-{{ $review->id }}" action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        @if (session('error'))
            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "error",
                    title: "{{ session('error') }}"
                });
            </script>
        @endif



    </div>



    <script>
        $(document).ready(function() {
            document.getElementById("mobile_submit_button").addEventListener("click", function() {
                document.getElementById("reservation_form").submit();
            });
        });
    </script>
@endsection
