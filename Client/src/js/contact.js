// Specify the API endpoint for user data
const apiUrl = 'http://127.0.0.1/email/list';

if(window.addEventListener) {
    window.addEventListener('load',fetchContacts,false); //W3C
} else {
    window.attachEvent('onload',fetchContacts); //IE
}

function fetchContacts() {
    // Make a GET request using the Fetch API
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(userData => {
            // Process the retrieved user data
            console.log('User Data:', userData);

            // Clear the table body
            document.querySelector('tbody').innerHTML = '';

            // Create a new table row for each user
            userData.message.forEach(user => {
                // Create a new table row
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="px-4 py-2">${user.id}</td>
                    <td class="px-4 py-2">${user.name}</td>
                    <td class="px-4 py-2">${user.email}</td>
                    <td class="px-4 py-2">${user.created_at}</td>
                    <td class="px-4 py-2"><a href="#" class="delete-contact" data-contact-id="${user.id}">Delete</a></td>
                `;
                // Add the new row to the table body
                document.querySelector('tbody').appendChild(newRow);

                // Add an event listener to the delete link
                newRow.querySelector('.delete-contact').addEventListener('click', function(event) {
                    event.preventDefault();
                    const contactId = event.target.getAttribute('data-contact-id');
                    console.log('Delete contact:', contactId);
                    deleteContact(contactId);
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function deleteContact(contactId) {
    fetch('http://127.0.0.1/email/delete/' + contactId, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        else {
            // Remove the deleted contact from table via data-contact-id.
            document.querySelector(`[data-contact-id="${contactId}"]`).parentNode.parentNode.remove();

        }
        return response.json();
    });
}

// Add event listener to form
document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    console.log('Form submitted:', name, email);

    fetch('http://127.0.0.1/email/create', {
        method: 'POST',
        body: JSON.stringify({name: name, email_address: email})
    })
    .then(response=>response.json())
    .then(response => {
        // After form submission then display message to user
        console.log(response)
        alert(response.message);

        if (response.message === 'Contact Created') {
            document.getElementById('contact-form').reset();
            fetchContacts();
        }
        else {
            alert('Something went wrong please try again later.');
        }
    })
    .catch(error => {
        alert('Something went wrong please try again later.');
        console.error('Error:', error);
    });

});
