 <?php require_once './template/base.php'; ?>

<h2>Ajouter une tâche</h2>
<form action="" method="post">
            <label>
                <span>Titre : </span>
                <input type="text" name="title" 
                 required>
            </label>
            <label>
                <span>Description : </span>
                <input type="textarea" name="description" 
                     required>
            </label>
            <label>
                <span>Date de début : </span>
                <input type="datetime-local" name="start" required>
            </label>
            <label>
                <span>Date butoir : </span>
                <input type="datetime-local" name="deadline" required>
            </label>
            <label>
                <span>Type : </span>
                <select name="type">
                    <option value="normal" selected>Normal</option>
                    <option value="urgent">Urgent</option>
                    <option value="faible">Faible</option>
                </select>
            </label>
            <label>
                <span>Option : </span>
                <select name="option" id='option'>
                    <option value="ongoing">En cours</option>
                    <option value="finished">Terminer</option>
                </select>
            </label>
            <input type="datetime-local" name="completed" id="completed">

            <button>Valider</button>
        </form>