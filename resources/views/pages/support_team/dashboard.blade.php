@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

@if(Qs::userIsTeamSA())
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $users->where('user_type', 'student')->count() }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                </div>

                <div class="ml-3 align-self-center">
                    <i class="icon-users4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $users->where('user_type', 'teacher')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Total Teachers</span>
                </div>

                <div class="ml-3 align-self-center">
                    <i class="icon-users2 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-pointer icon-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $users->where('user_type', 'admin')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Total Administrators</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-indigo-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-user icon-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $users->where('user_type', 'parent')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Total Parents</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Piano -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Piano</h5>
    </div>
    <div class="card-body">
        <div id="piano">
            <button class="piano-key" data-note="Q">Q</button>
            <button class="piano-key" data-note="W">W</button>
            <button class="piano-key" data-note="E">E</button>
            <button class="piano-key" data-note="R">R</button>
            <button class="piano-key" data-note="T">T</button>
            <button class="piano-key" data-note="Y">Y</button>
            <button class="piano-key" data-note="U">U</button>
        </div>
    </div>
</div>
<!-- Fin del Piano -->

<!-- Simon Says -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Simon Says</h5>
    </div>
    <div class="card-body">
        <div id="simon-says">
            <button class="play-btn">Play Sequence</button>
            <div id="message"></div> <!-- Aquí se mostrará el mensaje -->
        </div>
    </div>
</div>
<!-- Fin de Simon Says -->

@endsection

@section('head')
<style>
    .piano-key {
        width: 80px;
        height: 300px;
        margin: 0 30px;
        background-color: #fff;
        border: 1px solid #333;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .piano-key.active {
        background-color: #add8e6; /* Cambia el color de fondo cuando la tecla está activa a azul claro */
        box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.8); /* Añade un efecto de sombra azul cuando la tecla está activa */
    }

    #message {
        margin-top: 20px;
        font-size: 18px;
    }

</style>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const pianoKeys = document.querySelectorAll('.piano-key');
    const audioFiles = {
        'Q': 'assets/re.wav',
        'W': 'assets/mi.wav',
        'E': 'assets/fa.wav',
        'R': 'assets/sol.wav',
        'T': 'assets/la.wav',
        'Y': 'assets/si.wav',
        'U': 'assets/do.wav'
    };

    pianoKeys.forEach(key => {
        key.addEventListener('click', () => {
            playSound(key.dataset.note);
            key.classList.add('active');
            setTimeout(() => key.classList.remove('active'), 300);
        });
    });

    document.addEventListener('keydown', event => {
        const key = event.key.toUpperCase();
        if (key in audioFiles) {
            const pressedKey = document.querySelector(`[data-note="${key}"]`);
            if (pressedKey) {
                playSound(key);
                pressedKey.classList.add('active');
                setTimeout(() => pressedKey.classList.remove('active'), 300);
            }
        }
    });

    function playSound(note) {
        const audio = new Audio(audioFiles[note]);
        audio.play();
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const pianoKeys = document.querySelectorAll('.piano-key');
    const audioFiles = {
        'Q': 'assets/re.wav',
        'W': 'assets/mi.wav',
        'E': 'assets/fa.wav',
        'R': 'assets/sol.wav',
        'T': 'assets/la.wav',
        'Y': 'assets/si.wav',
        'U': 'assets/do.wav'
    };

    const simonSaysButton = document.querySelector('.play-btn');
    simonSaysButton.addEventListener('click', playSimonSays);

    let simonSequence = [];
    let playerSequence = [];
    let sequenceLength = 4; // Longitud inicial de la secuencia
    let round = 1; // Ronda actual

    function playSimonSays() {
    simonSequence = generateSequence(sequenceLength);
    playNotes(simonSequence);
    simonSaysButton.disabled = true; // Deshabilita el botón de "Play Sequence"
    }   


    function generateSequence(length) {
        const notes = Object.keys(audioFiles);
        const sequence = [];
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * notes.length);
            sequence.push(notes[randomIndex]);
        }
        return sequence;
    }

    async function playNotes(sequence) {
    disablePlayerInput(); // Deshabilita la entrada del jugador mientras se reproduce la secuencia de Simon
    for (const note of sequence) {
        const key = document.querySelector(`[data-note="${note}"]`);
        if (key) {
            key.classList.add('active');
            await playSound(note);
            key.classList.remove('active');
            await sleep(500); // Espera 500ms entre cada nota
        }
    }
    enablePlayerInput(); // Habilita la entrada del jugador después de reproducir la secuencia
}

    function playSound(note) {
        return new Promise(resolve => {
            const audio = new Audio(audioFiles[note]);
            audio.play();
            audio.addEventListener('ended', resolve);
        });
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function enablePlayerInput() {
        pianoKeys.forEach(key => {
            key.addEventListener('click', handlePlayerInput);
        });
    }

    function disablePlayerInput() {
        pianoKeys.forEach(key => {
            key.removeEventListener('click', handlePlayerInput);
        });
    }

    function handlePlayerInput(event) {
        const selectedNote = event.target.dataset.note;
        playerSequence.push(selectedNote);
        if (playerSequence.length === simonSequence.length) {
            checkPlayerSequence();
        }
    }

    function checkPlayerSequence() {
    if (arraysEqual(playerSequence, simonSequence)) {
        const message = document.getElementById('message');
        message.textContent = `¡Has pasado a la ronda ${round}!`;
        if (round === 10) {
            message.textContent += ' ¡Felicidades! ¡Has ganado el juego!';
            resetGame();
        } else {
            round++;
            sequenceLength++;
            console.log('Ronda actual:', round);
            console.log('Longitud de la secuencia:', sequenceLength);
            playerSequence = [];
            simonSaysButton.disabled = false;
        }
    } else {
        alert('¡Has perdido! Inténtalo de nuevo.');
        resetGame();
    }
}


    function arraysEqual(arr1, arr2) {
        if (arr1.length !== arr2.length) return false;
        for (let i = 0; i < arr1.length; i++) {
            if (arr1[i] !== arr2[i]) return false;
        }
        return true;
    }

    function resetGame() {
        round = 1;
        sequenceLength = 4;
        playerSequence = [];
        simonSaysButton.disabled = false;
    }
});
</script>
@endsection