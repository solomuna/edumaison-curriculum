<?php

namespace Database\Seeders;

/**
 * Erreurs bloquantes des autres niveaux (Pre-Nursery à Class 2, Class 4),
 * audit du 18/10/2026 (docs/audits/2026-10-18-autres-niveaux.md) :
 * clés fausses, réponses justes refusées, mots français sans accents en
 * Nursery, faits erronés, et copies des exercices déjà corrigés en Class 3,
 * 5 et 6 (« Ti/Si », « head/elder », mvet).
 *
 * Même mécanisme que AuditClass356ContentFixSeeder : chaque correction
 * n'est appliquée que si la valeur trouvée à l'audit est toujours là.
 *
 *   php artisan db:seed --class=AuditOtherLevelsContentFixSeeder --force
 */
class AuditOtherLevelsContentFixSeeder extends AuditClass356ContentFixSeeder
{
    /** [id, chemin dans content, valeur trouvée à l'audit, valeur corrigée] */
    protected function fixes(): array
    {
        return [
            [2695, 'pairs.2.word', 'I am', 'She is'],
            [2695, 'pairs.3.word', 'I have', 'We have'],
            [2701, 'questions.0.options.3', 'un', 'une'],
            [2704, 'questions.0.options.3', 'le', 'les'],
            [2704, 'questions.1.options.3', 'la', 'les'],
            [2704, 'questions.2.options.3', 'le', 'les'],
            [2747, 'statement', 'Every word has at least one vowel.', 'Most words have at least one vowel.'],
            [2747, 'explanation', 'Yes! Every English word needs at least one vowel: A, E, I, O, or U.', 'Yes! Most words have a vowel: A, E, I, O or U. Sometimes Y works like a vowel, as in my and sky.'],
            [2761, 'questions', [['answer' => 1, 'options' => ['Pere', 'Mere', 'Soeur', 'Frere'], 'question' => 'Comment dit-on MOTHER en francais?'], ['answer' => 2, 'options' => ['Mere', 'Frere', 'Pere', 'Soeur'], 'question' => 'Comment dit-on FATHER en francais?'], ['answer' => 3, 'options' => ['Frere', 'Mere', 'Pere', 'Soeur'], 'question' => 'Comment dit-on SISTER en francais?']], [['answer' => 1, 'options' => ['Père', 'Mère', 'Sœur', 'Frère'], 'question' => 'Comment dit-on MOTHER en français ?'], ['answer' => 2, 'options' => ['Mère', 'Frère', 'Père', 'Sœur'], 'question' => 'Comment dit-on FATHER en français ?'], ['answer' => 3, 'options' => ['Frère', 'Mère', 'Père', 'Sœur'], 'question' => 'Comment dit-on SISTER en français ?']]],
            [2762, 'questions', [['answer' => 2, 'options' => ['Soeur', 'Grand-mere', 'Mere', 'Tante'], 'question' => 'C\'est la femme de mon pere. C\'est ma ___.'], ['answer' => 1, 'options' => ['Pere', 'Frere', 'Cousin', 'Oncle'], 'question' => 'C\'est le fils de ma mere. C\'est mon ___.'], ['answer' => 3, 'options' => ['Tante', 'Mere', 'Soeur', 'Grand-mere'], 'question' => 'C\'est la mere de ma mere. C\'est ma ___.']], [['answer' => 2, 'options' => ['Sœur', 'Grand-mère', 'Mère', 'Tante'], 'question' => 'C\'est la femme de mon père. C\'est ma ___.'], ['answer' => 1, 'options' => ['Père', 'Frère', 'Cousin', 'Oncle'], 'question' => 'C\'est le fils de ma mère. C\'est mon ___.'], ['answer' => 3, 'options' => ['Tante', 'Mère', 'Sœur', 'Grand-mère'], 'question' => 'C\'est la mère de ma mère. C\'est ma ___.']]],
            [2765, 'questions', [['answer' => 0, 'options' => ['Grand-pere', 'Grand-mere', 'Oncle', 'Tante'], 'question' => 'Comment dit-on GRANDFATHER en francais?'], ['answer' => 2, 'options' => ['Oncle', 'Cousin', 'Tante', 'Niece'], 'question' => 'Comment dit-on AUNT en francais?'], ['answer' => 3, 'options' => ['Frere', 'Oncle', 'Neveu', 'Cousin'], 'question' => 'Comment dit-on COUSIN en francais?']], [['answer' => 0, 'options' => ['Grand-père', 'Grand-mère', 'Oncle', 'Tante'], 'question' => 'Comment dit-on GRANDFATHER en français ?'], ['answer' => 2, 'options' => ['Oncle', 'Cousin', 'Tante', 'Nièce'], 'question' => 'Comment dit-on AUNT en français ?'], ['answer' => 3, 'options' => ['Frère', 'Oncle', 'Neveu', 'Cousin'], 'question' => 'Comment dit-on COUSIN en français ?']]],
            [2766, 'questions', [['answer' => 1, 'options' => ['Ma', 'Mon', 'Mes', 'Sa'], 'question' => '___ pere est grand.'], ['answer' => 1, 'options' => ['Mon', 'Ma', 'Mes', 'Sa'], 'question' => '___ mere est belle.'], ['answer' => 2, 'options' => ['Ma', 'Mes', 'Mon', 'Sa'], 'question' => '___ frere est petit.']], [['answer' => 1, 'options' => ['Ma', 'Mon', 'Mes', 'Sa'], 'question' => '___ père est grand.'], ['answer' => 1, 'options' => ['Mon', 'Ma', 'Mes', 'Sa'], 'question' => '___ mère est belle.'], ['answer' => 2, 'options' => ['Ma', 'Mes', 'Mon', 'Sa'], 'question' => '___ frère est petit.']]],
            [2775, 'questions', [['answer' => 2, 'options' => ['La', 'Les', 'Le', 'Une'], 'question' => '___ garcon joue.'], ['answer' => 2, 'options' => ['Le', 'Les', 'La', 'Un'], 'question' => '___ fille chante.'], ['answer' => 2, 'options' => ['Le', 'La', 'Les', 'Un'], 'question' => '___ chiens courent.']], [['answer' => 2, 'options' => ['La', 'Les', 'Le', 'Une'], 'question' => '___ garçon joue.'], ['answer' => 2, 'options' => ['Le', 'Les', 'La', 'Un'], 'question' => '___ fille chante.'], ['answer' => 2, 'options' => ['Le', 'La', 'Les', 'Un'], 'question' => '___ chiens courent.']]],
            [2763, 'pairs', [['word' => 'Pere', 'image' => 'Father'], ['word' => 'Mere', 'image' => 'Mother'], ['word' => 'Frere', 'image' => 'Brother'], ['word' => 'Soeur', 'image' => 'Sister']], [['word' => 'Père', 'image' => 'Father'], ['word' => 'Mère', 'image' => 'Mother'], ['word' => 'Frère', 'image' => 'Brother'], ['word' => 'Sœur', 'image' => 'Sister']]],
            [2763, 'question', 'Associe chaque mot francais a sa traduction.', 'Associe chaque mot français à sa traduction.'],
            [2768, 'pairs', [['word' => 'Grand-pere', 'image' => 'Grandfather'], ['word' => 'Grand-mere', 'image' => 'Grandmother'], ['word' => 'Oncle', 'image' => 'Uncle'], ['word' => 'Tante', 'image' => 'Aunt']], [['word' => 'Grand-père', 'image' => 'Grandfather'], ['word' => 'Grand-mère', 'image' => 'Grandmother'], ['word' => 'Oncle', 'image' => 'Uncle'], ['word' => 'Tante', 'image' => 'Aunt']]],
            [2062, 'items.0.sentence', 'We use ___ and water to make clay soft.', 'We mix clay with ___ to make it soft.'],
            [2062, 'items.0.answer', 'hands', 'water'],
            [2559, 'items.0.prompt', 'We must not ___ in class.', 'We must walk, we must not ___ in class.'],
            [2562, 'items.0.prompt', 'We have two ___.', 'We see with our two ___.'],
            [110, 'sentences.0.alternatives', null, ['crayon']],
            [114, 'sentences.1.alternatives', null, ['bateau', 'barque']],
            [114, 'sentences.3.alternatives', null, ['vélo', 'moto']],
            [114, 'sentences.4.alternatives', null, ['moto']],
            [1386, 'statement', 'Fufu corn and njama njama is a popular dish in the Western region.', 'Fufu corn and njama njama is a popular dish in the North West region.'],
            [2777, 'questions.0.question', 'What shape do you get when you fold a square piece of paper in half?', 'What shape do you get when you fold a square piece of paper in half from corner to corner?'],
            [210, 'sentences.0.text', 'The ___ is red.', 'I read my red ___ at school.'],
            [293, 'sentences.0.text', 'The ___ is red.', 'I read my red ___ at school.'],
            [376, 'sentences.0.text', 'The ___ is red.', 'I read my red ___ at school.'],
            [424, 'words', ['big', 'a', 'is', 'dog', 'The'], ['big', 'is', 'dog', 'The']],
            [424, 'answer', ['The', 'dog', 'is', 'a', 'big'], ['The', 'dog', 'is', 'big']],
            [2576, 'items.0.alternatives', null, ['flat', 'compound']],
            [2576, 'items.1.alternatives', null, ['house']],
            [474, 'questions.0.text', 'Des chats = ___ chats', 'Le chat → ___ chats'],
            [474, 'questions.0.options.3', 'des', 'la'],
            [474, 'questions.0.explanation', 'Des chats = PLUSIEURS (more than one). Le pluriel s\'indique par \'des\'.', 'Le chat → les chats : au pluriel, « le » devient « les ».'],
            [2131, 'questions.0.text', 'Des chats = ___ chats', 'Le chat → ___ chats'],
            [2131, 'questions.0.options.3', 'des', 'la'],
            [234, 'questions.0.text', 'Des chats = ___ chats', 'Le chat → ___ chats'],
            [234, 'questions.0.options.3', 'des', 'la'],
            [234, 'questions.0.explanation', 'Des chats = PLUSIEURS chats. Le pluriel ajoute -s en general.', 'Le chat → les chats : au pluriel, « le » devient « les ».'],
            [317, 'questions.0.text', 'Des chats = ___ chats', 'Le chat → ___ chats'],
            [317, 'questions.0.options.3', 'des', 'la'],
            [317, 'questions.0.explanation', 'Des chats = plusieurs chats.', 'Le chat → les chats : au pluriel, « le » devient « les ».'],
            [400, 'questions.0.text', 'Des chats = ___ chats', 'Le chat → ___ chats'],
            [400, 'questions.0.options.3', 'des', 'la'],
            [400, 'questions.0.explanation', 'Des chats = plusieurs chats.', 'Le chat → les chats : au pluriel, « le » devient « les ».'],
            [473, 'questions.0.text', 'Le ___ est dans la maison. (chat)', '___ chat est dans la maison.'],
            [473, 'questions.0.options', ['la', 'le', 'les', 'un'], ['La', 'Le', 'Les', 'Une']],
            [473, 'questions.0.explanation', 'Chat est masculin: LE chat. La chatte (feminin). Le chat est dans la maison.', 'Chat est masculin : le chat.'],
            [2130, 'questions.0.text', 'Le ___ est dans la maison. (chat)', '___ chat est dans la maison.'],
            [2130, 'questions.0.options', ['la', 'le', 'les', 'un'], ['La', 'Le', 'Les', 'Une']],
            [2601, 'items.0.alternatives', null, ['ship']],
            [2642, 'items.0.alternatives', null, ['walk', 'come']],
            [2642, 'items.1.alternatives', null, ['bright']],
            [1818, 'questions.0.answer', 1, 2],
            [409, 'setA', ['Mango', 'Banana', 'Orange', 'Pineapple'], ['Mango', 'Banana', 'Orange', 'Pineapple', 'Coconut', 'Avocado']],
            [409, 'intersection', ['Mango', 'Orange'], ['Mango', 'Orange', 'Coconut', 'Avocado']],
            [352, 'questions.0.options.1', '2/4', '1/3'],
            [352, 'questions.0.answer', 1, 2],
            [1099, 'questions.0.options.3', '4/8', '1/8'],
            [130, 'sentences.4.text', 'Une ___ contient des informations sur l\'actualité.', 'Un ___ contient des informations sur l\'actualité.'],
            [130, 'sentences.4.answer', 'lettre', 'journal'],
            [393, 'questions.0.options.2', 'shall', 'are'],
            [393, 'questions.0.options.3', 'should', 'did'],
            [2996, 'answer', 'Ti/Si', 'Ti'],
            [2996, 'alternatives', null, ['Si']],
            [2896, 'answer', 'head/elder', 'head'],
            [2896, 'alternatives', null, ['elder']],
            [2896, 'sentence', 'The head of the family who makes important decisions is called the family _____.', 'The person who makes important decisions for the family is called the family ___.'],
            [2992, 'questions.3.question', 'Which instrument is associated with the Baka people of Cameroon?', 'Which instrument is associated with the Beti and Fang peoples of Cameroon?'],
        ];
    }
}
