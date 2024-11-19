<form action="{{ route('comment.store') }}" method="post">
    @csrf
    <table>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name" value=""></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" value=""></td>
        </tr>
        <tr>
            <td>Message</td>
            <td><textarea name="message"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button>submit</button>
            </td>
        </tr>
    </table>
</form>
