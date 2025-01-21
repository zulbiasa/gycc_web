<div class="header">
    <!-- <h2>Welcome to the Dashboard, {{ $username }}</h2> -->
    <!-- <h1>Welcome to the Dashboard, {{ $username }}!</h1> -->
    <div class="welcome-section">
    <h1>Welcome to the Dashboard, {{ $username }}!</h1>
        <p>Your Trusted Companion in Every Step with GYCC+</p>
    </div>

    <div class="user-info">
        <img src="{{ $imageUrl ?? 'https://www.w3schools.com/howto/img_avatar.png' }}" alt="User Avatar">
        <!-- <span>Administrator</span> -->
        <span>{{ $role }}</span>
    </div>
</div>
