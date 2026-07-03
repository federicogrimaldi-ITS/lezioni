package negozio.demo.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import negozio.demo.entity.ProdottoOrtofrutticolo;

public interface ProdottoRepository extends JpaRepository<ProdottoOrtofrutticolo, Long> {
}