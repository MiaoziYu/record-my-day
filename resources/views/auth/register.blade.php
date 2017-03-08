<form method="POST" action="/register">

    {{ csrf_field() }}

    <input type="text" name="name" placeholder="name">
    <input type="email" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <input type="password" name="confirmPassword" placeholder="confirm password">
    <button type="submit">Submit</button>
</form>