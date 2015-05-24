<?php
/** @var \Entity\Charity $charity */
?>
<html>
    <head>
        <link rel='stylesheet' href='/assets/styles/fullcalendar.min.css'/>
        <script src='/assets/scripts/jquery.js'></script>
        <script src='/assets/scripts/moment.min.js'></script>
        <script src='/assets/scripts/fullcalendar.min.js'></script>
        <script src='/assets/scripts/giverhub.js?1'></script>
        <script src='/assets/scripts/modules/volcal.js'></script>
        <style>
            .embedded-vol-cal .fc-toolbar .fc-center h2 {
                font-size: 13px;
                margin-top: 9px;
            }
            .embedded-vol-cal .fc-toolbar {
                font-size: 0.7em;
            }
            .embedded-vol-cal .fc-view-container {
                font-size: 0.64em;
            }
            body {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body class="embedded-vol-cal">
        <div class="vol-cal-block">
        <div class="vol-cal embed"
             data-cal-height="309"
             data-timezone="<?php echo htmlspecialchars(\Entity\CharityVolunteeringOpportunity::$php_time_zones['UTC-05:00']); ?>"
             data-nonprofit-id="<?php echo $charity->getId(); ?>"
             data-events="<?php echo htmlspecialchars(json_encode($charity->getVolunteeringOpportunities())); ?>"></div>
        </div>
    </body>
</html>