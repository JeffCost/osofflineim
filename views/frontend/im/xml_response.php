<?php if(isset($messages) and !empty($messages)): ?>
<?xml version="1.0" encoding="utf-8"?>
<ArrayOfGridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<?php foreach($messages as $msg): ?>
    <?php echo $msg->message ?>
<?php endforeach; ?>
</ArrayOfGridInstantMessage>
<?php else: ?>
<?xml version="1.0" encoding="utf-8"?>
<ArrayOfGridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
</ArrayOfGridInstantMessage>
<?php endif; ?>