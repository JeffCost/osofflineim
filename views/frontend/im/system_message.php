<GridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <fromAgentID>00000000-0000-0000-0000-000000000000</fromAgentID>
  <fromAgentName>Server</fromAgentName>
  <toAgentID><?php echo $agent_id ?></toAgentID>
  <dialog>19</dialog>
  <fromGroup>false</fromGroup>
  <message>You still have <?php $count ?> messages to receive. Please relog to receive remaining messages.</message>
  <imSessionID><?php $random_uuid ?></imSessionID>
  <offline>1</offline>
  <Position>
    <X>0</X>
    <Y>0</Y>
    <Z>0</Z>
  </Position>
  <binaryBucket />
  <ParentEstateID>000</ParentEstateID>
  <RegionID><?php $system_region_uuid ?></RegionID>
  <timestamp><?php echo time() ?></timestamp>
</GridInstantMessage>