<div class="header">
    <div class="welcome-section">
        <h1>Welcome to Golden Year Care Connect+</h1>
        <p>Your Trusted Companion in Every Step with GYCC+</p>
    </div>

    <div class="user-info">
        <img src="{{ $imageUrl ?? 'https://www.w3schools.com/howto/img_avatar.png' }}" alt="User Avatar">
        <div>
            <span class="username">{{ $name }}</span><br>
            <span class="role">{{ $role }}</span>
        </div>
    </div>
</div>

<style>

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #0056b3, #007bff);
        color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .welcome-section h1 {
        font-size: 2rem;
        margin: 0;
        color: #ffffff;
    }

    .welcome-section p {
        font-size: 1rem;
        margin: 5px 0 0;
        color: #d1e8ff;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-info img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 2px solid white;
        object-fit: cover;
    }

    .user-info .username {
        font-size: 1.2rem;
        font-weight: bold;
        color: white !important; /* Force the username text to white */
    }

    .user-info .role {
        font-size: 1rem;
        font-style: italic;
        color: white !important; /* Force the role text to white */
    }
</style>
