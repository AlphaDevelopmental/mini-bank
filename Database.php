<?php
declare(strict_types=1);

class Database {
    public array $data;
    
    public function __construct(private string $file) {
        $this->load();
    }
    
    private function load(): void {
        if (!file_exists($this->file)) {
            $this->data = [
                'users' => [],
                'accounts' => [],
                'transactions' => [],
                'notifications' => [],
                'pendingRequests' => []
            ];
            $this->save();
        }
        $this->data = json_decode(file_get_contents($this->file), true);
    }
    
    private function save(): void {
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
    }
    
    public function insert(string $table, array $record): array {
        $record['id'] = $this->generateId($table);
        $this->data[$table][] = $record;
        $this->save();
        return $record;
    }
    
    public function find(string $table, string $key, mixed $value): ?array {
        foreach ($this->data[$table] as $record) {
            if ($record[$key] === $value) return $record;
        }
        return null;
    }
    
    public function findAll(string $table, ?string $key = null, mixed $value = null): array {
        if ($key === null) return $this->data[$table];
        return array_values(array_filter(
            $this->data[$table], 
            fn($r) => $r[$key] === $value
        ));
    }
    
    public function update(string $table, int $id, array $updates): ?array {
        foreach ($this->data[$table] as &$record) {
            if ($record['id'] === $id) {
                $record = [...$record, ...$updates];
                $this->save();
                return $record;
            }
        }
        return null;
    }
    
    public function delete(string $table, int $id): bool {
        $this->data[$table] = array_values(array_filter(
            $this->data[$table],
            fn($r) => $r['id'] !== $id
        ));
        $this->save();
        return true;
    }
    
    private function generateId(string $table): int {
        return count($this->data[$table]) + 1;
    }
}
?>