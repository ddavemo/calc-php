<?php
require_once 'MathOperations.php';

$display = '0';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num1 = filter_input(INPUT_POST, 'num1', FILTER_VALIDATE_FLOAT);
    $num2 = filter_input(INPUT_POST, 'num2', FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
    $operation = $_POST['operation'] ?? '';

    $math = new MathOperations();

    try {
        switch ($operation) {
            case 'add': $display = $math->add($num1, $num2); break;
            case 'subtract': $display = $math->subtract($num1, $num2); break;
            case 'multiply': $display = $math->multiply($num1, $num2); break;
            case 'divide': $display = $math->divide($num1, $num2); break;
            case 'power': $display = $math->power($num1, $num2); break;
            case 'squareRoot': $display = $math->squareRoot($num1); break;
            case 'percentage': $display = $math->percentage($num1, $num2); break;
            case 'logarithm': $display = isset($num2) ? $math->logarithm($num1, $num2) : $math->logarithm($num1); break;
            default: $message = "Error: Invalid operation";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multipurpose Calculator</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="calculator">
        <h1>Multipurpose Calculator</h1>

        <form id="calcForm" method="post">
            <input type="text" id="display" name="result" value="<?= htmlspecialchars($display) ?>" readonly>
            <input type="hidden" id="num1" name="num1" value="<?= htmlspecialchars($display) ?>">
            <input type="hidden" id="num2" name="num2" value="">
            <input type="hidden" id="operation" name="operation" value="">

            <div class="keypad">
                <button type="button" class="btn num">7</button>
                <button type="button" class="btn num">8</button>
                <button type="button" class="btn num">9</button>
                <button type="button" class="btn op" data-op="divide">÷</button>
                <button type="button" class="btn fn" data-op="squareRoot">√</button>

                <button type="button" class="btn num">4</button>
                <button type="button" class="btn num">5</button>
                <button type="button" class="btn num">6</button>
                <button type="button" class="btn op" data-op="multiply">×</button>
                <button type="button" class="btn fn" data-op="power">xʸ</button>

                <button type="button" class="btn num">1</button>
                <button type="button" class="btn num">2</button>
                <button type="button" class="btn num">3</button>
                <button type="button" class="btn op" data-op="subtract">-</button>
                <button type="button" class="btn fn" data-op="logarithm">log</button>

                <button type="button" class="btn num">0</button>
                <button type="button" class="btn num">.</button>
                <button type="button" class="btn clear">C</button>
                <button type="button" class="btn op" data-op="add">+</button>
                <button type="button" class="btn fn" data-op="percentage">%</button>

                <button type="submit" class="btn wide calculate">=</button>
            </div>
        </form>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('calcForm');
            const display = document.getElementById('display');
            const num1 = document.getElementById('num1');
            const num2 = document.getElementById('num2');
            const operation = document.getElementById('operation');

            let isNewNumber = true;
            let isResult = false;

            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const val = btn.textContent;
                    const op = btn.getAttribute('data-op');

                    if (btn.classList.contains('num')) {
                        if (isNewNumber || display.value === '0' || isResult) {
                            display.value = val === '.' ? '0.' : val;
                            isNewNumber = false;
                        } else {
                            display.value += val;
                        }
                        isResult = false;
                    } else if (btn.classList.contains('op') || btn.classList.contains('fn')) {
                        num1.value = display.value;
                        operation.value = op;
                        isNewNumber = true;
                    } else if (btn.classList.contains('clear')) {
                        display.value = '0';
                        num1.value = '';
                        num2.value = '';
                        operation.value = '';
                        isNewNumber = true;
                        isResult = false;
                    } else if (btn.classList.contains('calculate')) {
                        if (operation.value) {
                            if (operation.value === 'squareRoot') {
                                form.submit();
                            } else {
                                num2.value = display.value;
                                form.submit();
                            }
                        }
                    }

                    if (!btn.classList.contains('calculate')) {
                        num2.value = '';
                    }
                });
            });

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                fetch('index.php', {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newDisplay = doc.getElementById('display');
                    if (newDisplay) {
                        display.value = newDisplay.value;
                        num1.value = newDisplay.value;
                        isResult = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    display.value = 'Error';
                });
            });
        });
    </script>
</body>
</html>