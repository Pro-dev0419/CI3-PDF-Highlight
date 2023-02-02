<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PDF Viewer</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="./../../assets/images/favicon.ico">

    <!-- STYLES -->

    <style {csp-style-nonce}>
        * {
            transition: background-color 300ms ease, color 300ms ease;
        }

        *:focus {
            background-color: rgba(221, 72, 20, .2);
            outline: none;
        }

        html,
        body {
            color: rgba(33, 37, 41, 1);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 16px;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        section {
            margin: 0 auto;
            max-width: 1100px;
            padding: 2.5rem 1.75rem 3.5rem 1.75rem;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

    <section class="relative">
        <?php if ($this->session->flashdata('status')) { ?>
            <div class="bg-green-400 border border-red-400 text-white-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">
                    <?php echo $this->session->flashdata('status'); ?>
                </strong>
            </div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">
                    <?php echo $this->session->flashdata('error'); ?>
                </strong>
            </div>
        <?php } ?>
        <button id="btn-nHighlight" class="nHighlight fixed bg-green-200 text-white p-1 rounded-md">
            <img id="icon-nHighlight" src="./../../assets/images/pen.png" width="20px" height="20px" />
        </button>
        <div class="flex flex-row justify-center align-center my-5">
            <button id="btn-save1"
                class="btn w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded active:bg-green-200">Save</button>
        </div>
        <h1 class="text-bold font-heading text-xl">HighLight Testing Section</h1>
        <p id="nHighlight-text">
            London is the capital city of England. It is the most populous city in the United Kingdom, with a
            metropolitan area of over 13 million inhabitants.
            Standing on the River Thames, London has been a major settlement for two millennia, its history going back
            to its founding by the Romans, who named it Londinium.
            London is the capital city of England. It is the most populous city in the United Kingdom, with a
            metropolitan area of over 13 million inhabitants.
            Standing on the River Thames, London has been a major settlement for two millennia, its history going back
            to its founding by the Romans, who named it Londinium.
            London is the capital city of England. It is the most populous city in the United Kingdom, with a
            metropolitan area of over 13 million inhabitants.
            Standing on the River Thames, London has been a major settlement for two millennia, its history going back
            to its founding by the Romans, who named it Londinium.
        </p>
    </section>

    <section class="pdf-panel">
        <div class="m-2">
            <form action="/upload" method="POST" enctype="multipart/form-data" id="form-upload-pdf">
                <input
                    class="w-75 text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="file-upload" type="file" name="pdf-file">
                <input
                    class="btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded active:bg-green-200"
                    type="submit" value="Upload">
            </form>
        </div>
        <div class="overflow-x-auto border border-gray-500 shadow-lg rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            File Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            control
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="pdf-record1 bg-white border-b dark:bg-gray-800 dark:border-gray-700  hover:bg-gray-400">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        </td>
                        <td scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap hidden dark:text-white pdf-url">
                            <?= $pdf_url ?>
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <button data-modal-target="large-modal" data-modal-toggle="large-modal"
                                class="pdf-record block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">
                                Open
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section>


        <div id="large-modal" tabindex="-1"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
            <div class="relative w-full h-full max-w-4xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                            PDF VIEWER
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="large-modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="pdf-panel p-5 flex flex-col">
                            <div class="flex flex-row justify-center align-center my-5">
                                <button id="btn-save"
                                    class="btn w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded active:bg-green-200">Save</button>
                            </div>
                            <div class="rounded-md overflow-hidden">
                                <div class="bg-[rgba(0,0,0,.5)] px-5 py-3">
                                    <button id="btn-drag"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded active:bg-green-200">@</button>
                                    <button id="zoomout"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded active:bg-green-200">-</button>
                                    <button id="zoomin"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded active:bg-green-200">+</button>
                                    <select id="select-zoom"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 rounded active:bg-green-200">
                                        <option value="50">50%</option>
                                        <option value="100" selected>100%</option>
                                        <option value="200">200%</option>
                                    </select>
                                </div>
                                <div
                                    class="relative h-[30rem] border border-[1rem] border-[rgba(0,0,0,.3)] bg-[rgba(0,0,0,.1)] rounded-b-md mb-5 overflow-scroll">
                                    <button id="btn-highlight"
                                        class="highlight fixed bg-green-200 text-white p-1 rounded-md z-[100]">
                                        <img id="icon-highlight" src="./../../assets/images/pen.png" width="20px"
                                            height="20px" />
                                    </button>
                                    <div id="pdf-viewer"
                                        class="draggable ui-widget-content bg-white min-h-full p-5 mx-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
    <script src="./../../assets/js/home.js"></script>

    <script>
        $(document).ready(function () {
            var dataURL = $('.pdf-url').html();
            $('#pdf-viewer').load('<?= $pdf_url ?>', function () {
                // $('#myModal').modal({ show: true });
            });
        });

    </script>

</body>

</html>