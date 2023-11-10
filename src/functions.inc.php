<?php


function parseFrontMatter(string $input) {
    $pattern = '/^---\s+(.*?)\s+---\s*(.*)$/s';
    preg_match($pattern, $input, $matches);

    if (count($matches) < 3) {
        // No front matter found, return empty metadata and the original input as content
        return ['metadata' => [], 'content' => $input];
    }

    // Parse YAML front matter
    try {
        // Parse YAML front matter
        $metadata = phore_yaml_decode($matches[1]);

    } catch (Exception $e) {
        // Find the line number of the error
        preg_match_all("/\n/", substr($input, 0, strpos($input, $matches[1])), $lines);
        $lineNumber = count($lines[0]) + 1; // Adding 1 because line numbering starts from 1

        throw new Exception("Error parsing YAML at line $lineNumber: " . $e->getMessage());
    }

    // The rest of the content
    $content = $matches[2];

    return ['metadata' => $metadata, 'content' => $content];
}


function serializeFrontMatter($metadata, string $content): string {
    // Convert the metadata to YAML format

    $yaml = phore_yaml_encode($metadata);

    // Remove starting and ending tags of yaml_emit
    $yaml = preg_replace('/^---\s+/', '', $yaml);
    $yaml = preg_replace('/\n...$/', '', $yaml);
    $yaml = rtrim($yaml, "\n");

    // Combine the YAML front matter and the content
    return "---\n" . $yaml . "\n---\n" . $content;
}
